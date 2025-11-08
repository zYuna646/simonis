@props(['mataPelajaran' => null, 'gurus' => []])

<form method="POST" action="{{ $mataPelajaran ? route('admin.mata-pelajaran.update', $mataPelajaran->id) : route('admin.mata-pelajaran.store') }}">
    @csrf
    @if($mataPelajaran) @method('PUT') @endif

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
        <input type="text" name="name" id="name" value="{{ old('name', $mataPelajaran ? $mataPelajaran->name : '') }}" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            required>
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="desc" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea name="desc" id="desc" rows="4" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('desc', $mataPelajaran ? $mataPelajaran->desc : '') }}</textarea>
        @error('desc')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="mb-4">
        <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-1">Guru Pengajar</label>
        <select name="guru_id" id="guru_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- Pilih Guru --</option>
            @foreach(App\Models\User::role('guru')->get() as $guru)
                <option value="{{ $guru->id }}" {{ old('guru_id', $mataPelajaran ? $mataPelajaran->guru_id : '') == $guru->id ? 'selected' : '' }}>
                    {{ $guru->name }}
                </option>
            @endforeach
        </select>
        @error('guru_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end space-x-2">
        <a href="{{ route('admin.mata-pelajaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
            Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            {{ $mataPelajaran ? 'Update' : 'Simpan' }}
        </button>
    </div>
</form>