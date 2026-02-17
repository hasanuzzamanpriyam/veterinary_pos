<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::table('permissions')->insert([
            ['name' => 'message-view', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'message-delete', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'message-reply', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $roles = \Spatie\Permission\Models\Role::whereIn('name', ['Super Admin', 'Admin', 'Manager'])->get();

        foreach ($roles as $role) {
            $role->syncPermissions(array_merge(
                $role->permissions->pluck('name')->toArray(),
                ['message-view', 'message-reply']
            ));
        }

        $staffRole = \Spatie\Permission\Models\Role::where('name', 'Staff')->first();
        if ($staffRole) {
            $staffRole->givePermissionTo('message-view');
        }

        $viewerRole = \Spatie\Permission\Models\Role::where('name', 'Viewer')->first();
        if ($viewerRole) {
            $viewerRole->givePermissionTo('message-view');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('permissions')->whereIn('name', ['message-view', 'message-delete', 'message-reply'])->delete();
    }
};
