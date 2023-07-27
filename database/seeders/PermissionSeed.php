<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['title' => 'user_management_access',],
            ['title' => 'user_management_create',],
            ['title' => 'user_management_edit',],
            ['title' => 'user_management_view',],
            ['title' => 'user_management_delete',],
            ['title' => 'permission_access',],
            ['title' => 'permission_create',],
            ['title' => 'permission_edit',],
            ['title' => 'permission_view',],
            [ 'title' => 'permission_delete',],
            [ 'title' => 'role_access',],
            [ 'title' => 'role_create',],
            [ 'title' => 'role_edit',],
            [ 'title' => 'role_view',],
            [ 'title' => 'role_delete',],
            [ 'title' => 'user_access',],
            [ 'title' => 'user_create',],
            [ 'title' => 'user_edit',],
            [ 'title' => 'user_view',],
            [ 'title' => 'user_delete',],
            [ 'title' => 'studios_access',],
            [ 'title' => 'studios_create',],
            [ 'title' => 'studios_edit',],
            [ 'title' => 'studios_view',],
            [ 'title' => 'studios_delete',],
            [ 'title' => 'booking_access',],
            [ 'title' => 'booking_create',],
            [ 'title' => 'booking_edit',],
            [ 'title' => 'booking_view',],
            [ 'title' => 'booking_delete',],
            [ 'title' => 'bookingpaket_access',],
            [ 'title' => 'bookingpaket_create',],
            [ 'title' => 'bookingpaket_edit',],
            [ 'title' => 'bookingpaket_view',],
            [ 'title' => 'bookingpaket_delete',],
            [ 'title' => 'services_access',],
            [ 'title' => 'services_create',],
            [ 'title' => 'services_edit',],
            [ 'title' => 'services_view',],
            [ 'title' => 'services_delete',],
        ];

            Permission::insert($permissions);

    }
}
