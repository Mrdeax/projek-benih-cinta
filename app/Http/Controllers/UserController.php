<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // ⚙️ Fitur filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Urutkan berdasarkan nama + pagination
        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas,member',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        if ($validated['role'] === 'member') {
            $memberCode = 'MBR-' . date('Ymd') . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            Member::create([
                'user_id' => $user->id,
                'member_code' => $memberCode,
                'join_date' => date('Y-m-d'),
                'total_purchase' => 0
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,member',
            'phone' => 'required|string',
            'address' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if ($validated['role'] === 'member' && !$user->member) {
            $memberCode = 'MBR-' . date('Ymd') . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            Member::create([
                'user_id' => $user->id,
                'member_code' => $memberCode,
                'join_date' => date('Y-m-d'),
                'total_purchase' => 0
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        if ($user->member) {
            $user->member->delete();
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
