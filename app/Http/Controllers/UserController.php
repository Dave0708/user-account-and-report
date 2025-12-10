<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show lists grouped by role: admins, staffs, and normal users.
     * Each group has its own paginator so you can page them separately in the view.
     */
    public function index(Request $request)
    {
        // Adjust per-page values as you prefer
        $perPage = 12;

        // Admins
        $admins = User::with('roleInfo')
            ->whereHas('roleInfo', function ($q) {
                $q->where('role_name', 'admin');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'admins_page');

        // Staff
        $staffs = User::with('roleInfo')
            ->whereHas('roleInfo', function ($q) {
                $q->where('role_name', 'staff');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'staffs_page');

        // Normal users (no role or other roles)
        $normalUsers = User::with('roleInfo')
            ->whereDoesntHave('roleInfo', function ($q) {
                $q->whereIn('role_name', ['admin', 'staff']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'users_page');

        return view('users.index', compact('admins', 'staffs', 'normalUsers'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'contact' => ['nullable', 'string', 'max:225'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'integer', 'exists:roles,id'],
        ]);

        User::create([
            'username' => $request->username,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $id . ',user_id'],
            'contact' => ['nullable', 'string', 'max:225'],
            'role' => ['required', 'integer', 'exists:roles,id'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->username = $request->username;
        $user->contact = $request->contact;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        // Use 'user_id' as the column name since that's your primary key
        $user = User::where('user_id', $id)->firstOrFail();

        // Check if user is trying to delete their own account
        if ($user->user_id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}