<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[0];
        });

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name|max:255',
        ]);

        try {
            Permission::create([
                'name' => $request->name,
                'guard_name' => 'web' // Explicitly set guard_name
            ]);

            // Clear permission cache
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return redirect()->route('permissions.index')
                ->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|max:255|unique:permissions,name,' . $permission->id,
        ]);

        try {
            $permission->update(['name' => $request->name]);

            // Clear permission cache
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return redirect()->route('permissions.index')
                ->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if user has permission to delete permissions (via middleware)
        // The permission middleware already handles the check

        try {
            $permission->delete();

            // Clear permission cache
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return redirect()->route('permissions.index')
                ->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting permission: ' . $e->getMessage());
        }
    }
}
