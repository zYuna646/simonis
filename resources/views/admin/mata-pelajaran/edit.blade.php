@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Edit Mata Pelajaran</h2>
    </div>

    <x-admin.mata-pelajaran-form :mataPelajaran="$mataPelajaran" />
</div>
@endsection