{{-- Ini halaman filter awal --}}
@vite('resources/css/app.css')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto p-6">
    <p class="text-sm text-gray-600 mb-4">
        Ditemukan {{ $complaints->total() }} pengaduan
        @if(request('search')) dengan kata kunci "<strong>{{ request('search') }}</strong>" @endif
        @if(request('status')) dan status <strong>{{ request('status') }}</strong> @endif
        @if(request('jenis_aduan')) jenis <strong>{{ request('jenis_aduan') }}</strong> @endif
        @if(request('date')) pada tanggal <strong>{{ request('date') }}</strong> @endif
    </p>


    @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-screen bg-white border border-gray-300 rounded-xl shadow-md">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2 border">Jenis Pelapor</th>
                    <th class="px-4 py-2 border">Nomor urut</th>
                    <th class="px-4 py-2 border">Kode Aduan</th>
                    <th class="px-4 py-2 border">Nomor Saluran</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Tanggal Aduan</th>
                    <th class="px-4 py-2 border">Jam Aduan</th>
                    <th class="px-4 py-2 border">Nomor Handphone</th>
                    <th class="px-4 py-2 border">Alamat</th>
                    <th class="px-4 py-2 border">Jenis Aduan</th>
                    <th class="px-4 py-2 border">Isi Aduan</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                    <th class="px-4 py-2 border">Edit</th>
                    <th class="px-4 py-2 border">hapus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $complaint)
                    <tr class="border-b hover:bg-gray-100">
                        <td>{{ $complaint->jenis_pelapor == 'personal' ? 'Pelanggan' : 'Umum' }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->id }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->kode_aduan }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->nomor_saluran }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->nama }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->date }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->created_at }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->nomor_handphone }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->alamat }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->jenis_aduan }}</td>
                        <td class="px-4 py-2 border">{{ $complaint->isi_aduan }}</td>
                        <td class="px-4 py-2 border text-center">
                            <span
                                class="px-2 py-1 rounded text-white 
                                                                                                                                                                                                                                                                                            {{ $complaint->status == 'Selesai' ? 'bg-green-500' : ($complaint->status == 'Pending' ? 'bg-yellow-500' : 'bg-gray-300') }}">
                                {{ $complaint->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('admin.show', $complaint->id) }}"
                                class="text-amber-100 bg-blue-500 hover:bg-blue-400 transform rounded px-2 py-2">Lihat</a>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('admin.complaint.edit', $complaint->id) }}"
                                class="text-amber-100 bg-blue-500 hover:bg-blue-400 transform rounded px-2 py-2">edit</a>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <form action="{{ route('complaint.destroy', $complaint->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-center"> {{ $complaints->links() }}</div>
</div>
<div class="mt-6 text-center">
    <a href="{{ route('admin.index') }}" class="text-blue-500 hover:underline">
        ← Kembali ke dashboard
    </a>
</div>