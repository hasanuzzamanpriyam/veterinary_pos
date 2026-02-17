<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class UserRoleController extends Controller
{
    /**
     * Display a listing of users with their roles.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(20);
        $totalUsers = User::count();
        return view('admin.users.roles', compact('users', 'totalUsers'));
    }
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required_without:phone|nullable|email:rfc,dns|unique:users,email',

            'phone' => [
                'required_without:email',
                'nullable',
                'string',
                'max:20',
                'regex:/^(?:\+8801|8801|01)[3-9]\d{8}$/',
                'unique:users,phone',
            ],

            'role' => 'required|exists:roles,name',

            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required_without' => 'Either email or phone number is required.',
            'phone.required_without' => 'Either email or phone number is required.',
            'email.unique' => 'This email is already registered.',
            'phone.unique' => 'This phone number is already registered.',
            'phone.regex' => 'Please enter a valid Bangladeshi phone number.',
        ]);



        DB::beginTransaction();
        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Assign role to user
            $user->assignRole($request->role);

            DB::commit();
            return redirect()->route('users.roles.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Show the form for editing user roles.
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.edit-roles', compact('user', 'roles', 'userRoles'));
    }
    /**
     * Update user roles.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Prevent removing Super Admin from user ID 1
        if ($user->id === 1 && !in_array('Super Admin', $request->roles ?? [])) {
            return redirect()->back()
                ->with('error', 'Cannot remove Super Admin role from the primary admin user.');
        }
        $request->validate([
            'roles' => 'array'
        ]);
        DB::beginTransaction();
        try {
            $user->syncRoles($request->roles ?? []);
            DB::commit();
            return redirect()->route('users.roles.index')
                ->with('success', 'User roles updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating user roles: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Delete a user (Super Admin only).
     */
    public function destroy(User $user)
    {
        // Check if user has permission to delete users (via middleware)
        // The permission middleware already handles the check
        // Prevent deletion of the primary admin user (ID 1)
        if ($user->id === 1) {
            return redirect()->back()
                ->with('error', 'Cannot delete the primary admin user.');
        }
        // Prevent Super Admin from deleting themselves
        if (auth()->user()->id === $user->id) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }
        try {
            // Delete user's profile photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Delete user's banner photo if exists
            if ($user->banner_photo_path) {
                Storage::disk('public')->delete($user->banner_photo_path);
            }
            $user->delete();
            return redirect()->route('users.roles.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
