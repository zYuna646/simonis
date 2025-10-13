@props(['teachers', 'kela' => null])

<form method="POST" action="{{ $kela ? route('admin.kelas.update', $kela->id) : route('admin.kelas.store') }}">
    @csrf
    @if($kela) @method('PUT') @endif

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
        <input type="text" name="name" id="name" value="{{ old('name', $kela ? $kela->name : '') }}" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            required>
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
        <select name="user_id" id="user_id" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            required>
            <option value="">-- Pilih Guru --</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ (old('user_id', $kela ? $kela->user_id : '') == $teacher->id) ? 'selected' : '' }}>
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>
        @error('user_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end space-x-2">
        <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
            Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            {{ $kela ? 'Update' : 'Simpan' }}
        </button>
    </div>
</form>