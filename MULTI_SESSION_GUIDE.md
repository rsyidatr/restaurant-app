# Multi-Session Authentication System

## Overview
Sistem multi-session memungkinkan Anda untuk login dengan beberapa akun secara bersamaan menggunakan guard yang berbeda. Setiap guard memiliki session terpisah sehingga tidak saling mengganggu.

## Fitur
- ✅ Login dengan multiple akun sekaligus
- ✅ Session terpisah untuk setiap guard (admin, staff, customer, regular)
- ✅ Switch antar session dengan mudah
- ✅ Logout individual tanpa mempengaruhi session lain
- ✅ Real-time monitoring session aktif
- ✅ Interface yang user-friendly

## Available Guards
1. **Admin** - Untuk akses admin dengan role admin
2. **Staff** - Untuk karyawan (pelayan, koki, dll)
3. **Customer** - Untuk pelanggan
4. **Regular** - Session default Laravel

## Cara Penggunaan

### 1. Akses Multi-Session Dashboard
Buka: `http://localhost/INTERN/project_restaurant/public/multi-session`

### 2. Login Multiple Accounts
- Pilih guard yang diinginkan (Admin, Staff, Customer, Regular)
- Masukkan kredensial untuk setiap guard
- Setiap login akan membuat session terpisah

### 3. Switch Between Sessions
- Klik tombol "Buka Sesi" pada session yang ingin diakses
- Anda akan diarahkan ke dashboard sesuai role

### 4. Logout Individual
- Klik tombol "Logout" pada session tertentu
- Session lain tetap aktif

## Routes

```php
// Multi-Session Dashboard
GET /multi-session

// Login dengan guard tertentu
POST /multi-session/login/{guard}

// Logout dari guard tertentu  
POST /multi-session/logout/{guard}

// Switch ke session tertentu
GET /multi-session/switch/{guard}
```

## Technical Implementation

### Guards Configuration
```php
// config/auth.php
'guards' => [
    'web' => ['driver' => 'session', 'provider' => 'users'],
    'admin' => ['driver' => 'session', 'provider' => 'users'],
    'staff' => ['driver' => 'session', 'provider' => 'users'],
    'customer' => ['driver' => 'session', 'provider' => 'users'],
],
```

### Session Management
- Setiap guard menggunakan session name yang berbeda
- Format: `{session_cookie_name}_{guard_name}`
- Contoh: `laravel_session_admin`, `laravel_session_staff`

### Middleware
- `MultiSessionAuth` middleware mengatur session per guard
- Secara otomatis switch session berdasarkan guard

## Use Cases

### 1. Development & Testing
- Test berbagai role secara bersamaan
- Debug permission dan access control
- Simulasi user behavior

### 2. Admin Management
- Monitor customer activity sambil mengelola admin
- Switch cepat antar role untuk troubleshooting

### 3. Staff Training
- Demonstrasi sistem dengan multiple roles
- Training dengan real-time switching

## Security Considerations

1. **Session Isolation**: Setiap guard memiliki session terpisah
2. **CSRF Protection**: Semua form dilindungi CSRF token
3. **Authentication Check**: Middleware memastikan user authenticated
4. **Session Timeout**: Mengikuti konfigurasi Laravel session

## Troubleshooting

### Session Conflict
Jika terjadi conflict session:
```bash
php artisan session:clear
php artisan cache:clear
```

### Route Issues
Jika route tidak dikenali:
```bash
php artisan route:clear
php artisan route:cache
```

### Permission Issues
Pastikan user memiliki role yang sesuai dengan guard yang digunakan.

## Example Usage

```php
// Login sebagai admin
Auth::guard('admin')->attempt($credentials);

// Check jika admin logged in
if (Auth::guard('admin')->check()) {
    $admin = Auth::guard('admin')->user();
}

// Logout dari admin guard saja
Auth::guard('admin')->logout();
```

## Future Enhancements
- [ ] Session sharing antar tab browser
- [ ] Real-time notifications antar session
- [ ] Session analytics dan monitoring
- [ ] Auto-logout berdasarkan inactivity
- [ ] Session backup dan restore

---

**Note**: Sistem ini sangat berguna untuk development dan testing. Untuk production, pertimbangkan security implications dan batasi akses sesuai kebutuhan.
