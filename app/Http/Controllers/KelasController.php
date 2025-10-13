<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelas = Kelas::with('user')->get();
        return view('admin.kelas.index', compact('kelas'));
    }
    
    public function card(): View
    {
        if (auth()->user()->hasRole('admin')) {
            // Admin melihat semua kelas
            $kelas = Kelas::with('user')->get();
        } else {
            // Guru hanya melihat kelas yang di-assign ke mereka
            $kelas = Kelas::with('user')
                ->where('user_id', auth()->id())
                ->get();
        }
        
        return view('admin.kelas.card', compact('kelas'));
    }

    public function create(): View
    {
        $teachers = User::role('guru')->get();
        return view('admin.kelas.create', compact('teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela): View
    {
        $teachers = User::role('guru')->get();
        return view('admin.kelas.edit', compact('kela', 'teachers'));
    }

    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $kela->update($request->all());

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        $kela->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
