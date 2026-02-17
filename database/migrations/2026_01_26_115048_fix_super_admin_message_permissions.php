<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $messagePermissions = Permission::whereIn('name', ['message-view', 'message-delete', 'message-reply'])->get();

        $roles = Role::whereIn('name', ['Super Admin', 'Admin', 'Manager'])->get();

        foreach ($roles as $role) {
            foreach ($messagePermissions as $permission) {
                if (!$role->hasPermissionTo($permission->name)) {
                    $role->givePermissionTo($permission);
                }
            }
        }

        $staffRole = Role::where('name', 'Staff')->first();
        if ($staffRole) {
            foreach ($messagePermissions as $permission) {
                if ($permission->name === 'message-view' && !$staffRole->hasPermissionTo('message-view')) {
                    $staffRole->givePermissionTo($permission);
                }
            }
        }

        $viewerRole = Role::where('name', 'Viewer')->first();
        if ($viewerRole) {
            foreach ($messagePermissions as $permission) {
                if ($permission->name === 'message-view' && !$viewerRole->hasPermissionTo('message-view')) {
                    $viewerRole->givePermissionTo($permission);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
