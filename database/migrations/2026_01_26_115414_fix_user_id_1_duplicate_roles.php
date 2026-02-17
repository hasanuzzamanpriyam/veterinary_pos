<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = User::find(1);
        if ($user && $user->roles->count() > 1) {
            $superAdminRole = Role::where('name', 'Super Admin')->first();
            if ($superAdminRole) {
                $user->syncRoles([$superAdminRole->id]);
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
