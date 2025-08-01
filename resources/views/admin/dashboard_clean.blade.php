@extends('layouts.admin_clean')

@section('title', 'Dasbor')
@section('page-title', 'Ringkasan Dasbor')

@section('content')
<div class="stats-grid">
    <!-- Total Users -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>{{ number_format($totalUsers) }}</h3>
            <p>Total Pengguna</p>
        </div>
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>{{ number_format($totalOrders) }}</h3>
            <p>Total Pesanan</p>
        </div>
        <div class="stat-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p>Total Pendapatan</p>
        </div>
        <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
    
    <!-- Today's Orders -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>{{ number_format($todayOrders) }}</h3>
            <p>Pesanan Hari Ini</p>
            <small style="color: #6b7280; font-size: 12px;">Pendapatan: Rp {{ number_format($todayRevenue, 0, ',', '.') }}</small>
        </div>
        <div class="stat-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
    </div>
</div>

<div class="content-grid">
    <!-- Recent Orders -->
    <div class="content-card">
        <div class="card-header">
            <h3>Pesanan Terbaru</h3>
        </div>
        <div class="card-content">
            @if($recentOrders->count() > 0)
                @foreach($recentOrders as $order)
                <div class="order-item">
                    <div class="order-info">
                        <h4>#{{ $order->id }} - {{ $order->user->name ?? 'Tamu' }}</h4>
                        <p>
                            {{ ucfirst($order->order_type) }}
                            @if($order->table)
                                - Meja {{ $order->table->table_number }}
                            @endif
                        </p>
                        <p style="font-size: 12px; color: #9ca3af;">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="order-amount">
                        <div class="price">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
                
                <div style="text-align: center; margin-top: 16px;">
                    <a href="{{ route('admin.orders.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                        Lihat semua pesanan →
                    </a>
                </div>
            @else
                <div class="no-content">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Belum ada pesanan</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Table Status -->
    <div class="content-card">
        <div class="card-header">
            <h3>Status Meja</h3>
        </div>
        <div class="card-content">
            <div class="table-status-item">
                <div class="status-indicator">
                    <div class="status-dot dot-green"></div>
                    <span>Tersedia</span>
                </div>
                <span style="font-weight: 600;">{{ $tableStats['available'] }}</span>
            </div>
            
            <div class="table-status-item">
                <div class="status-indicator">
                    <div class="status-dot dot-yellow"></div>
                    <span>Direservasi</span>
                </div>
                <span style="font-weight: 600;">{{ $tableStats['reserved'] }}</span>
            </div>
            
            <div class="table-status-item">
                <div class="status-indicator">
                    <div class="status-dot dot-red"></div>
                    <span>Terisi</span>
                </div>
                <span style="font-weight: 600;">{{ $tableStats['occupied'] }}</span>
            </div>
            
            <div class="table-status-item">
                <div class="status-indicator">
                    <div class="status-dot dot-blue"></div>
                    <span>Pembersihan</span>
                </div>
                <span style="font-weight: 600;">{{ $tableStats['cleaning'] }}</span>
            </div>
            
            <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                <a href="{{ route('admin.tables.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                    Kelola meja →
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Popular Menu Items -->
<div class="content-card" style="grid-column: 1 / -1;">
    <div class="card-header">
        <h3>Item Menu Terpopuler</h3>
    </div>
    <div class="card-content">
        @if($popularMenus->count() > 0)
            @foreach($popularMenus as $menu)
            <div class="order-item">
                <div class="order-info">
                    <h4>{{ $menu->name }}</h4>
                    <p>Total dipesan: {{ $menu->total_ordered }} kali</p>
                </div>
                <div class="order-amount">
                    <span style="color: #3b82f6; font-weight: 600; font-size: 18px;">
                        {{ $menu->total_ordered }}
                    </span>
                </div>
            </div>
            @endforeach
        @else
            <div class="no-content">
                <i class="fas fa-utensils"></i>
                <p>Data menu tidak tersedia</p>
            </div>
        @endif
    </div>
</div>
@endsection
