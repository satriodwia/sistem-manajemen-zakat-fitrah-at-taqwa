<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===== PERMISSIONS =====
        // Muzakki
        Permission::create(['name' => 'view muzakki']);
        Permission::create(['name' => 'create muzakki']);
        Permission::create(['name' => 'edit muzakki']);
        Permission::create(['name' => 'delete muzakki']);

        // Mustahik
        Permission::create(['name' => 'view mustahik']);
        Permission::create(['name' => 'create mustahik']);
        Permission::create(['name' => 'edit mustahik']);
        Permission::create(['name' => 'delete mustahik']);
        Permission::create(['name' => 'approve mustahik']);

        // Zakat Payments
        Permission::create(['name' => 'view zakat payments']);
        Permission::create(['name' => 'create zakat payments']);
        Permission::create(['name' => 'edit zakat payments']);
        Permission::create(['name' => 'delete zakat payments']);
        Permission::create(['name' => 'confirm zakat payments']);

        // Sedekah Payments
        Permission::create(['name' => 'view sedekah payments']);
        Permission::create(['name' => 'create sedekah payments']);
        Permission::create(['name' => 'edit sedekah payments']);
        Permission::create(['name' => 'delete sedekah payments']);
        Permission::create(['name' => 'confirm sedekah payments']);

        // Laporan
        Permission::create(['name' => 'view laporan keuangan']);
        Permission::create(['name' => 'export laporan']);

        // Payment Methods
        Permission::create(['name' => 'view payment methods']);
        Permission::create(['name' => 'manage payment methods']);

        // Users & Roles
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage roles']);

        // ===== ROLES =====

        // 1. SUPER ADMIN - Full Access
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. ADMIN ZAKAT - Kelola Pembayaran & Keuangan
        $adminZakat = Role::create(['name' => 'admin_zakat']);
        $adminZakat->givePermissionTo([
            // Muzakki
            'view muzakki',
            'create muzakki',
            'edit muzakki',
            
            // Mustahik (read only)
            'view mustahik',
            
            // Zakat Payments (full)
            'view zakat payments',
            'create zakat payments',
            'edit zakat payments',
            'delete zakat payments',
            'confirm zakat payments',
            
            // Sedekah Payments (full)
            'view sedekah payments',
            'create sedekah payments',
            'edit sedekah payments',
            'delete sedekah payments',
            'confirm sedekah payments',
            
            // Laporan
            'view laporan keuangan',
            'export laporan',
            
            // Payment Methods (read only)
            'view payment methods',
        ]);

        // 3. PANITIA ZAKAT (RT) - Input Mustahik
        $panitiaZakat = Role::create(['name' => 'panitia_zakat']);
        $panitiaZakat->givePermissionTo([
            // Muzakki (read only)
            'view muzakki',
            
            // Mustahik (full CRUD tapi tidak bisa approve)
            'view mustahik',
            'create mustahik',
            'edit mustahik',
            'delete mustahik',
        ]);

        // ===== ASSIGN ROLES TO USERS =====
        
        // Cari user admin yang sudah ada
        $adminUser = User::where('email', 'admin@admin.com')->first();
        
        if ($adminUser) {
            // Assign sebagai Super Admin
            $adminUser->assignRole('super_admin');
        }

        // Buat user demo untuk testing (optional)
        
        // Admin Zakat
        $adminZakatUser = User::create([
            'name' => 'Bendahara Zakat',
            'email' => 'bendahara@masjid.com',
            'password' => bcrypt('password'),
        ]);
        $adminZakatUser->assignRole('admin_zakat');

        // Panitia Zakat (RT)
        $panitiaUser = User::create([
            'name' => 'Pengurus RT 01',
            'email' => 'rt01@masjid.com',
            'password' => bcrypt('password'),
        ]);
        $panitiaUser->assignRole('panitia_zakat');
    }
}