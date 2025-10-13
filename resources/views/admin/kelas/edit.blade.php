@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Edit Kelas</h2>
    </div>

    <x-admin.kelas-form :teachers="$teachers" :kela="$kela" />
</div>
@endsection