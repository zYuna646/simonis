@extends('dashboard')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-container .select2-selection--multiple {
        min-height: 42px;
        border-color: #d1d5db;
        border-radius: 0.375rem;
    }
    .select2-container--bootstrap-5 .select2-selection {
        padding: 0.25rem;
        border-color: #d1d5db;
        box-shadow: none;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #d1d5db;
    }
    .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field {
        border-radius: 0.25rem;
        border-color: #d1d5db;
        padding: 0.5rem;
    }
    .select2-container--bootstrap-5 .select2-selection__choice {
        background-color: #e5e7eb !important;
        border: 1px solid #d1d5db !important;
        color: #374151 !important;
        border-radius: 0.25rem !important;
        padding: 0.25rem 0.5rem !important;
        margin-top: 0.25rem !important;
    }
</style>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Siswa ke Kelas {{ $kelas->nama }}</h2>
        <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="mt-6">
        <form action="{{ route('admin.kelas.siswa.store', $kelas->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="siswa_ids" class="block text-sm font-medium text-gray-700 mb-1">Pilih Siswa</label>
                <select name="siswa_ids[]" id="siswa_ids" multiple
                    class="form-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->name }} ({{ $s->email }})
                        </option>
                    @endforeach
                </select>
                @error('siswa_ids')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.kelas.siswa.index', $kelas->id) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Tambahkan Siswa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#siswa_ids').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Siswa",
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada data siswa";
                },
                searching: function() {
                    return "Mencari...";
                },
                inputTooShort: function() {
                    return "Ketik untuk mencari siswa";
                }
            },
            closeOnSelect: false,
            dropdownCssClass: 'select2--large'
        });
    });
</script>
@endpush