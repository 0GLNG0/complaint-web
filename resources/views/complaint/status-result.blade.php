@extends('layouts.app')

@section('title', 'Hasil Status Pengaduan')

@section('content')
    {{-- <div class="bg-white flex items-center justify-center min-h-screen"> --}}
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg min-h-screen">
            <h2 class="text-2xl font-bold mb-4 text-center">Hasil Pencarian</h2>

            @if($complaints->isEmpty())
                <p class="text-center text-gray-500">Tidak ada pengaduan ditemukan.</p>
            @else
                @foreach($complaints as $complaint)
                    <div class="p-4 border-b">
                        @php
    $statusClass = match ($complaint->status) {
        'Pending' => 'text-red-600 bg-red-100 px-2 py-1 rounded text-sm font-semibold',
        'Selesai' => 'text-green-600 bg-green-100 px-2 py-1 rounded text-sm font-semibold',
        default => 'text-gray-600 bg-gray-100 px-2 py-1 rounded text-sm font-semibold'
    };
@endphp

<p>
    <strong>Status:</strong>
    <span class="{{ $statusClass }}">
        {{ $complaint->status }}
    </span>
</p>

                        {{-- <p><strong>Status:</strong> <span class="font-semibold">{{ $complaint->status }}</span></p> --}}
                        <p><strong>Nomor saluran:</strong> {{ $complaint->nomor_saluran }}</p>
                        <p><strong>Nama:</strong> {{ $complaint->nama }}</p>
                        <p><strong>Jenis Aduan:</strong> {{ $complaint->jenis_aduan }}</p>
                        <p><strong>Alamat:</strong> {{ $complaint->alamat }}</p>
                        <p><strong>kelurahan:</strong> {{ $complaint->kelurahan }}</p>
                        <p><strong>Tanggal Aduan:</strong> {{ $complaint->created_at->format('d-m-Y') }}</p>
                        <p><strong>Jenis Aduan:</strong> {{ $complaint->jenis_aduan }}</p>
                        <p><strong>Isi Aduan:</strong> {{ $complaint->isi_aduan }}</p>
                        <p><strong>Terakhir Diupdate:</strong> {{ $complaint->updated_at->diffForHumans() }}</p>
                        <a href="{{ route('complaint.download', $complaint->id) }}"
                            class="bg-blue-500 rounded-2xl text-white text-center btn-primary block transition-transform transform hover:scale-105 hover:bg-blue-600"
                            target="_blank">
                            Download Resi
                        </a>
                    </div>
                @endforeach
            @endif
            <a href="{{ route('complaint.checkStatusForm') }}" class="text-blue-500 block text-center hover:underline">
                ← Kembali ke daftar</a>
        </div>
        {{--
    </div> --}}
@endsection