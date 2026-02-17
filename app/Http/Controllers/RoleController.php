<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[0];
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
            'permissions' => 'array'
        ]);

        DB::beginTransaction();
        try {
            // Force web guard to matches existing permissions
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            // Debug: Log received permissions
            Log::info('Creating role: ' . $request->name . ' with guard web');
            Log::info('Permissions received: ' . json_encode($request->permissions));

            if ($request->has('permissions') && is_array($request->permissions)) {
                // Ensure permissions are strings
                $permissions = array_map('strval', $request->permissions);
                $role->syncPermissions($permissions);
                Log::info('Permissions synced: ' . $role->permissions->count());
            } else {
                Log::warning('No permissions provided or permissions is not an array');
            }

            DB::commit();

            $permissionCount = $role->permissions->count();
            return redirect()->route('roles.index')
                ->with('success', 'Role "' . $role->name . '" created successfully with ' . $permissionCount . ' permissions.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating role: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $this->authorize('update', $role);

        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[0];
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $this->authorize('update', $role);

        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        // Prevent removing critical permissions from Super Admin role
        if ($role->name === 'Super Admin' && $request->has('permissions')) {
            $criticalPermissions = [
                'role-edit',
                'role-delete',
                'user-role-assign'
            ];

            foreach ($criticalPermissions as $permission) {
                if (!in_array($permission, $request->permissions)) {
                    return redirect()->back()
                        ->with('error', "Cannot remove '{$permission}' permission from Super Admin role. This is required for system security.");
                }
            }
        }

        DB::beginTransaction();
        try {
            $role->update(['name' => $request->name]);

            // Debug info
            Log::info('Updating role: ' . $role->name);
            Log::info('New permissions: ' . json_encode($request->permissions));

            if ($request->has('permissions') && is_array($request->permissions)) {
                 $permissions = array_map('strval', $request->permissions);
                 $role->syncPermissions($permissions);
            } else {
                 // If no permissions sent (unchecked all), sync empty array
                 $role->syncPermissions([]);
            }

            DB::commit();
            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating role: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Check if user has permission to delete roles (via middleware)
        // The permission middleware already handles the check

        // Prevent deletion of system roles
        $protectedRoles = ['Super Admin', 'Admin', 'Manager', 'Staff', 'Viewer'];
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->back()
                ->with('error', 'Cannot delete system role: ' . $role->name);
        }

        try {
            $role->delete();
            return redirect()->route('roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }
}
