<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.user', 'order.table']);
        
        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by order ID or customer name
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->whereHas('order', function($orderQuery) use ($request) {
                    $orderQuery->where('id', 'ILIKE', '%' . $request->search . '%')
                              ->orWhereHas('user', function($userQuery) use ($request) {
                                  $userQuery->where('name', 'ILIKE', '%' . $request->search . '%');
                              });
                });
            });
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total_payments' => Payment::count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_amount_today' => Payment::where('status', 'completed')
                                          ->whereDate('created_at', today())
                                          ->sum('amount'),
            'total_amount_month' => Payment::where('status', 'completed')
                                           ->whereBetween('created_at', [
                                               now()->startOfMonth(),
                                               now()->endOfMonth()
                                           ])
                                           ->sum('amount'),
            'cash_payments' => Payment::where('payment_method', 'cash')->count(),
            'card_payments' => Payment::where('payment_method', 'card')->count(),
        ];
        
        return view('admin.payments.index', compact('payments', 'stats'));
    }
    
    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.table', 'order.orderItems.menuItem']);
        
        return view('admin.payments.show', compact('payment'));
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,failed,refunded'
        ]);

        $oldStatus = $payment->status;
        $payment->update(['status' => $request->status]);

        // Update order status based on payment status
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $payment->order->update(['status' => 'completed']);
        } elseif ($request->status === 'failed' && $oldStatus === 'completed') {
            $payment->order->update(['status' => 'pending']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
            'status' => $payment->status
        ]);
    }

    public function processRefund(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembayaran yang sudah selesai yang bisa direfund'
            ]);
        }

        $payment->update(['status' => 'refunded']);
        $payment->order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Refund berhasil diproses'
        ]);
    }

    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $payments = Payment::where('status', 'completed')
                          ->whereBetween('created_at', [$startDate, $endDate])
                          ->with(['order.user', 'order.table'])
                          ->get();

        // Group by payment method
        $paymentMethods = $payments->groupBy('payment_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount')
            ];
        });

        // Daily revenue
        $dailyRevenue = $payments->groupBy(function($payment) {
            return $payment->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('amount');
        });

        $stats = [
            'total_revenue' => $payments->sum('amount'),
            'total_transactions' => $payments->count(),
            'average_transaction' => $payments->count() > 0 ? $payments->sum('amount') / $payments->count() : 0,
            'payment_methods' => $paymentMethods,
            'daily_revenue' => $dailyRevenue
        ];

        return view('admin.payments.report', compact('stats', 'startDate', 'endDate', 'payments'));
    }
    
    public function verify(Payment $payment)
    {
        // For cash payments that need verification
        if ($payment->payment_method === 'cash' && !$payment->verified_at) {
            $payment->update([
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'status' => 'completed'
            ]);
            
            // Update order payment status
            $payment->order->update([
                'payment_status' => 'paid',
                'status' => 'completed'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diverifikasi'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Pembayaran tidak dapat diverifikasi'
        ]);
    }
}
