<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role === 'user') {
                $query->where('is_admin', false);
            }
        }

        $users = $query->latest()->paginate(10);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'users' => User::where('is_admin', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_admin' => ['boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->has('is_admin');

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_admin' => ['boolean'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->has('is_admin');

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Prevent deleting current logged-in user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Toggle admin status
     */
    public function toggleAdmin(User $user)
    {
        // Prevent removing admin from current user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri!');
        }

        $user->update([
            'is_admin' => !$user->is_admin
        ]);

        $role = $user->is_admin ? 'Admin' : 'User';

        return back()->with('success', "User berhasil diubah menjadi {$role}!");
    }
}
