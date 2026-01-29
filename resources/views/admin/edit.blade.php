@vite('resources/css/app.css')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Data Pengaduan</h2>

        <form action="{{ route('admin.complaint.update', $complaint->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nomor Aduan (readonly) -->
                <div>
                    <label class="block text-gray-700 mb-1">Nomor Aduan</label>
                    <input type="text" value="{{ $complaint->kode_aduan }}" class="w-full bg-gray-100 p-2 rounded"
                        readonly>
                </div>

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-gray-700 mb-1">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $complaint->nama) }}"
                        class="w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500" required>

                    <label for="alamat" class="block text-gray-700 mb-1">alamat</label>
                    <input type="text" name="alamat" id="alamat" value="{{ old('alamat', $complaint->alamat) }}"
                        class="w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500" required>

                    <label for="isi_aduan" class="block text-gray-700 mb-1">Isi Aduan</label>
                    <input type="text" name="isi_aduan" id="isi_aduan"
                        value="{{ old('isi_aduan', $complaint->isi_aduan) }}"
                        class="w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500" required>

                    <label for="nomor_handphone" class="block text-gray-700 mb-1">Nomor Handphone</label>
                    <input type="text" name="nomor_handphone" id="nomor_handphone"
                        value="{{ old('nomor_handphone', $complaint->nomor_handphone) }}"
                        class="w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500" required>

                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.filter') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>