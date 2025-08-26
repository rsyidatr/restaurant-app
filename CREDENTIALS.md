# Test Credentials untuk Restaurant Management System

## Multi-Session Login Portal
URL: `/multi-session`

## Akun Testing yang Tersedia:

### 1. Admin
- **Email:** admin@test.com
- **Password:** password
- **Role:** admin
- **Access:** Admin dashboard dengan full access

### 2. Pelayan (Waiter)
- **Email:** waiter@test.com  
- **Password:** password
- **Role:** pelayan
- **Access:** Waiter dashboard dengan orders, tables, reservations management

### 3. Koki (Chef)
- **Email:** chef@test.com
- **Password:** password
- **Role:** koki
- **Access:** Kitchen dashboard dengan order processing, menu availability

### 4. Customer
- **Email:** customer@test.com
- **Password:** password123
- **Role:** customer
- **Access:** Customer interface untuk ordering

## Cara Menggunakan Multi-Session:

1. Buka `/multi-session`
2. Login dengan salah satu akun di atas sesuai dengan role yang ingin ditest
3. Sistem akan redirect ke dashboard yang sesuai
4. Bisa login multiple role secara bersamaan untuk testing workflow
5. Switch antar session menggunakan tombol "Buka Sesi"

## Dashboard URLs:
- Admin: `/admin/dashboard`
- Waiter: `/waiter/dashboard`
- Kitchen: `/kitchen/dashboard`  
- Customer: `/` (landing page)

## Error yang Sudah Diperbaiki:
- ✅ Route [staff.dashboard] not defined - Fixed
- ✅ Undefined variable $tables - Fixed  
- ✅ Role constraint violations - Fixed
- ✅ Missing user data - Fixed
