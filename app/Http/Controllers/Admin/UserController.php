<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ];
        
        // Tambahkan validasi untuk NKK jika role siswa atau orang tua
        if (in_array($request->role, ['siswa', 'orang tua'])) {
            $rules['nkk'] = 'required|string|max:255';
        }
        
        // Tambahkan validasi untuk NISN jika role siswa
        if ($request->role === 'siswa') {
            $rules['nisn'] = 'required|string|max:255';
        }
        
        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        
        // Tambahkan NKK dan NISN ke data user jika ada
        if ($request->filled('nkk')) {
            $userData['nkk'] = $request->nkk;
        }
        
        if ($request->filled('nisn')) {
            $userData['nisn'] = $request->nisn;
        }
        
        $user = User::create($userData);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }
        
        // Tambahkan validasi untuk NKK jika role siswa atau orang tua
        if (in_array($request->role, ['siswa', 'orang tua'])) {
            $rules['nkk'] = 'required|string|max:255';
        }
        
        // Tambahkan validasi untuk NISN jika role siswa
        if ($request->role === 'siswa') {
            $rules['nisn'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        // Tambahkan NKK dan NISN ke data user jika ada
        if ($request->filled('nkk')) {
            $userData['nkk'] = $request->nkk;
        }
        
        if ($request->filled('nisn')) {
            $userData['nisn'] = $request->nisn;
        }
        
        $user->update($userData);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Sync roles
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting self
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}