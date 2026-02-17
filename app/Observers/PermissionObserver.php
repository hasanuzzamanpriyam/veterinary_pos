<?php

namespace App\Observers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     *
     * @param  Permission  $permission
     * @return void
     */
    public function created(Permission $permission): void
    {
        try {
            $superAdminRole = Role::where('name', 'Super Admin')->first();

            if ($superAdminRole) {
                $superAdminRole->givePermissionTo($permission);
                Log::info("Permission '{$permission->name}' automatically assigned to Super Admin role.");
            } else {
                Log::warning("Super Admin role not found when creating permission '{$permission->name}'");
            }
        } catch (\Exception $e) {
            Log::error("Failed to assign permission '{$permission->name}' to Super Admin role: " . $e->getMessage());
        }
    }

    /**
     * Handle the Permission "updated" event.
     *
     * @param  Permission  $permission
     * @return void
     */
    public function updated(Permission $permission): void
    {
        //
    }

    /**
     * Handle the Permission "deleted" event.
     *
     * @param  Permission  $permission
     * @return void
     */
    public function deleted(Permission $permission): void
    {
        Log::info("Permission '{$permission->name}' deleted.");
    }

    /**
     * Handle the Permission "restored" event.
     *
     * @param  Permission  $permission
     * @return void
     */
    public function restored(Permission $permission): void
    {
        try {
            $superAdminRole = Role::where('name', 'Super Admin')->first();

            if ($superAdminRole) {
                $superAdminRole->givePermissionTo($permission);
                Log::info("Permission '{$permission->name}' automatically assigned to Super Admin role after restore.");
            }
        } catch (\Exception $e) {
            Log::error("Failed to assign restored permission '{$permission->name}' to Super Admin role: " . $e->getMessage());
        }
    }

    /**
     * Handle the Permission "force deleted" event.
     *
     * @param  Permission  $permission
     * @return void
     */
    public function forceDeleted(Permission $permission): void
    {
        Log::info("Permission '{$permission->name}' permanently deleted.");
    }
}
