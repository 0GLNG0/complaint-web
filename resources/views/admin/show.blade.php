@vite('resources/css/app.css')
<script src="https://cdn.tailwindcss.com"></script>
<div class="bg-gray-100 text-gray-800 my-5">

    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Detail Pengaduan</h2>

        <div class="space-y-3">
            <p><strong>Nomor Saluran:</strong> {{ $complaint->nomor_saluran }}</p>
            <p><strong>Nama:</strong> {{ $complaint->nama }}</p>
            <p><strong>Alamat:</strong> {{ $complaint->alamat }}</p>
            <p><strong>kelurahan:</strong> {{ $complaint->kelurahan }}</p>
            <p><strong>kecamatan:</strong> {{ $complaint->kecamatan }}</p>
            <p><strong>Nomor HP:</strong> {{ $complaint->nomor_handphone }}</p>
            <p><strong>Tanggal aduan:</strong> {{ $complaint->date }}</p>
            <p><strong>Jam Aduan:</strong> {{ $complaint->created_at }}</p>
            <p><strong>Jenis Aduan:</strong> {{ $complaint->jenis_aduan }}</p>
            <p><strong>Isi Aduan:</strong> {{ $complaint->isi_aduan }}</p>
            <p><strong>Status:</strong>
                <span
                    class="inline-block px-2 py-1 rounded-full text-sm 
                    {{ $complaint->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ $complaint->status }}
                </span>
            </p>
        </div>

        @if(strtolower($complaint->status) === 'pending')
    <form action="{{ route('admin.validate', $complaint->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="keterangan_admin">Keterangan Admin:</label>
        <textarea name="keterangan_admin" class="w-full border p-2 rounded" required></textarea>

        <button type="submit" class="mt-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Tandai Selesai
        </button>
    </form>
@endif



        <div class="mt-6 text-center">
            <a href="{{ route('admin.filter') }}" class="text-blue-500 hover:underline">
                ← Kembali ke daftar
            </a>
        </div>
    </div>
</div>