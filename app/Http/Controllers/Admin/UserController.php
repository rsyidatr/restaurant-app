<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        // Search by name or email
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'ILIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'ILIKE', '%' . $request->search . '%');
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'pelayan' => User::where('role', 'pelayan')->count(),
            'koki' => User::where('role', 'koki')->count(),
            'staff' => User::whereIn('role', ['pelayan', 'koki'])->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,customer,pelayan,koki',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Pengguna berhasil dibuat.');
    }
    
    public function show(User $user)
    {
        $user->load(['orders.orderItems.menuItem', 'reservations.table']);
        
        // Statistics for this user
        $userStats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('total_amount'),
            'total_reservations' => $user->reservations()->count(),
            'last_order' => $user->orders()->latest()->first()?->created_at,
        ];
        
        return view('admin.users.show', compact('user', 'userStats'));
    }
    
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,customer,pelayan,koki',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting the current admin user
        if (auth()->id() === $user->id) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        // Check if user has active orders or reservations
        $hasActiveOrders = $user->orders()->whereIn('status', ['pending', 'processing', 'ready'])->exists();
        $hasActiveReservations = $user->reservations()->whereIn('status', ['pending', 'confirmed'])->exists();

        if ($hasActiveOrders || $hasActiveReservations) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus pengguna yang memiliki pesanan atau reservasi aktif.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        // Prevent disabling the current admin user
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menonaktifkan akun Anda sendiri.'
            ]);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'message' => $user->is_active ? 'Pengguna diaktifkan' : 'Pengguna dinonaktifkan',
            'status' => $user->is_active
        ]);
    }
}
