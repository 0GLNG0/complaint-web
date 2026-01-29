@extends('layouts.app')

@section('title', 'Cek Status Pengaduan')

@section('content')
    <div class=" flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4 text-center">Cek Status Pengaduan</h2>

            @if(session('error'))
                <p class="text-red-500">{{ session('error') }}</p>
            @endif

            <form action="{{ route('complaint.checkStatus') }}" method="POST">
                @csrf
                <input type="text" name="keyword" placeholder="Masukkan No HP / No Saluran / Alamat"
                    class="border px-3 py-2 rounded w-full" required>
                <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Cek Status</button>

            </form>
            <a href="{{ route('complaint.create') }}" class="text-blue-500 hover:underline">
                ← Kembali</a>
        </div>
    </div>
@endsection