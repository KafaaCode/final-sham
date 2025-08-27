<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // الصلاحيات
        $permissions = [
            'اضافة مستخدم',
            'تعديل مستخدم',
            'حذف مستخدم',
            'عرض مستخدم',

            'اضافة صلاحية',
            'تعديل صلاحية',
            'حذف صلاحية',
            'عرض صلاحية',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // الأدوار
        $roles = [
            'المريض',
            'الدكتور',
            'موظف الاستقبال',
            'ممرض الجناح',
            'فني العمليات',
            'فني المخبر',
            'فني الأشعة',
        ];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(['name' => $roleName]);
        }

        // إنشاء دور Super Admin
        $superAdminRole = Role::updateOrCreate(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo($permissions);

        // إنشاء مستخدم Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '123456789',
                'password' => Hash::make('password')
            ]
        );
        $superAdmin->assignRole($superAdminRole);
    }
}
