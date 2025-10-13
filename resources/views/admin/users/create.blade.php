@extends('dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Pengguna Baru</h2>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <x-admin.user-form :roles="$roles" />
    </form>
</div>
@endsection