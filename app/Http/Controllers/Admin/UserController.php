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
        
        // Validasi NISN: wajib untuk Siswa dan Orang Tua (NISN Anak)
        if (in_array($request->role, ['siswa', 'orang_tua', 'orang tua'])) {
            $rules['nisn'] = 'required|string|max:255';
        }
        
        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        
        // Role-spesifik: set NISN dan relasi orang tua -> anak via NISN
        if ($request->role === 'siswa') {
            $userData['nisn'] = $request->nisn;
        } elseif (in_array($request->role, ['orang_tua', 'orang tua'])) {
            // Cari anak berdasarkan NISN; anak harus ber-role siswa
            $child = User::where('nisn', $request->nisn)
                ->whereHas('roles', function($q){ $q->where('name', 'siswa'); })
                ->first();
            if (!$child) {
                return back()->withErrors(['nisn' => 'NISN anak tidak ditemukan. Pastikan NISN benar dan anak sudah terdaftar sebagai siswa.'])->withInput();
            }
            // Simpan NISN anak di field nisn milik orang tua
            $userData['nisn'] = $request->nisn;
            // Opsional: salin NKK anak jika tersedia untuk kompatibilitas lama
            if (!empty($child->nkk)) {
                $userData['nkk'] = $child->nkk;
            }
        }
        // Jika ada NKK diinput manual (role lain), simpan apa adanya
        if ($request->filled('nkk') && !isset($userData['nkk'])) {
            $userData['nkk'] = $request->nkk;
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
        
        // Validasi NISN: wajib untuk Siswa dan Orang Tua (NISN Anak)
        if (in_array($request->role, ['siswa', 'orang_tua', 'orang tua'])) {
            $rules['nisn'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        // Role-spesifik: update NISN dan relasi orang tua -> anak via NISN
        if ($request->role === 'siswa') {
            $userData['nisn'] = $request->nisn;
            // NKK tidak diperlukan untuk siswa
        } elseif (in_array($request->role, ['orang_tua', 'orang tua'])) {
            $child = User::where('nisn', $request->nisn)
                ->whereHas('roles', function($q){ $q->where('name', 'siswa'); })
                ->first();
            if (!$child) {
                return back()->withErrors(['nisn' => 'NISN anak tidak ditemukan. Pastikan NISN benar dan anak sudah terdaftar sebagai siswa.'])->withInput();
            }
            $userData['nisn'] = $request->nisn;
            if (!empty($child->nkk)) {
                $userData['nkk'] = $child->nkk;
            } else {
                // Kosongkan NKK jika tidak digunakan lagi
                $userData['nkk'] = null;
            }
        } else {
            // Role lain: simpan NKK jika diisi
            if ($request->filled('nkk')) {
                $userData['nkk'] = $request->nkk;
            }
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