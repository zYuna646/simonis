@props(['user' => null, 'roles' => []])

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user ? $user->name : '') }}" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user ? $user->email : '') }}" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $user ? 'Password (kosongkan jika tidak ingin mengubah)' : 'Password' }}
        </label>
        <input type="password" name="password" id="password" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="mb-4">
        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
        <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ (old('role', $user && $user->hasRole($role->name) ? $role->name : '') == $role->name) ? 'selected' : '' }} class="capitalize">
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div id="nkk-field" class="mb-4" style="display: none;">
        <label for="nkk" class="block text-sm font-medium text-gray-700 mb-1">NKK</label>
        <input type="text" name="nkk" id="nkk" value="{{ old('nkk', $user ? $user->nkk : '') }}" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="(Tidak diperlukan untuk Siswa/Orang Tua)">
        @error('nkk')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div id="nisn-field" class="mb-4" style="display: none;">
        <label for="nisn" id="nisn-label" class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
        <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $user ? $user->nisn : '') }}" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan NISN">
        @error('nisn')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end mt-6">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md mr-2 hover:bg-gray-400 transition">
            Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            {{ $user ? 'Update' : 'Simpan' }}
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const nkkField = document.getElementById('nkk-field');
        const nisnField = document.getElementById('nisn-field');
        const nisnLabel = document.getElementById('nisn-label');
        
        // Initial check
        toggleFields(roleSelect.value);
        
        // On change
        roleSelect.addEventListener('change', function() {
            toggleFields(this.value);
        });
        
        function toggleFields(role) {
            // Normalisasi nilai role untuk variasi penamaan
            const isSiswa = role === 'siswa';
            const isOrangTua = role === 'orang_tua' || role === 'orang tua';

            // NKK tidak digunakan lagi untuk Siswa/Orang Tua
            nkkField.style.display = 'none';

            // NISN: tampil untuk Siswa dan Orang Tua
            if (isSiswa) {
                nisnLabel.textContent = 'NISN';
                nisnField.style.display = 'block';
            } else if (isOrangTua) {
                nisnLabel.textContent = 'NISN Anak';
                nisnField.style.display = 'block';
            } else {
                nisnField.style.display = 'none';
            }
        }
        
        // Check initial state if editing
        toggleFields(roleSelect.value);
    });
</script>