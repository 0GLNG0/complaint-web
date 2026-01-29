@extends('layouts.app')
@section('title', 'Form Pengaduan')
@section('content')

    <!-- Modal -->
    <!-- Modal Wrapper -->
    <div id="tatacaraModal"
        class="fixed inset-0 bg-black/50 flex justify-center items-center z-50 opacity-0 scale-95 pointer-events-none transition-all duration-300 ease-out">
        <!-- Konten Modal -->
        <div
            class="rounded-lg p-6 w-full max-w-xl shadow-lg relative overflow-y-auto max-h-[90vh] dark:bg-gray-800 transation-colors duration-300">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-500">✕</button>

            <h2 class="text-2xl font-bold mb-4 text-blue-700">Tata Cara Pengaduan</h2>
            <ol class="list-decimal ml-5 space-y-2 text-gray-700 dark:text-gray-300">
                <li>Masuk ke halaman Formulir Aduan.</li>
                <li>Isi data diri secara lengkap dan benar.</li>
                <li>Pilih kategori pengaduan sesuai masalah.</li>
                <li>Tulis deskripsi dengan jelas dan detail.</li>
                <li>Unggah bukti jika ada.</li>
                <li>Klik tombol Kirim Aduan.</li>
            </ol>

            <div class="mt-6 text-right">
                <button onclick="closeModal()"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 hover:scale-105 transition">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <div class=" p-2 rounded-lg w-full max-w-full md:max-w-3xl lg:max-w-4xl">
        <div class="bg-0 mb-5">
            <h2 class="text-4xl font-bold mb-10 text-center text-blue-700">Layanan Aduan Online PDAM Masyarakat</h2>
        </div>
        <h2 class="text-2xl font-bold mb-8 text-center text-white rounded bg-blue-700">Form Pengaduan</h2>

        <button onclick="openModal()"
            class="bg-blue-700 text-white px-4 w-full rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-500">
            Lihat Tata Cara Aduan
        </button>


        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-2 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        {{-- count --}}
        <div class="border border-indigo-500 rounded my-2">
            <p class="text-sm font-semibold">Aduan Hari Ini</p>
            <p class="text-sm font-bold text-blue-600">{{ $count }}</p>
        </div>

        {{-- form --}}
        <form action="{{ route('complaint.store') }}" method="POST">
            @csrf
            <div class="mb-4 ">
                {{-- jenis pelapor --}}
                <label class="block text-gray-700">Jenis Pelapor:</label>
                <select name="jenis_pelapor" id="jenis_pelapor" onchange="toggleNomorSaluran()"
                    class="w-full border rounded p-2 mb-4" required>
                    <option value="">Pilih Jenis Pelapor</option>
                    <option value="personal">Pelapor Personal (Pelanggan)</option>
                    <option value="umum">Pelapor Umum</option>
                </select>
                <div id="nomorSaluranField">
                    <label class="block text-gray-700">Nomor Saluran</label>
                    <input type="number" name="nomor_saluran"
                        class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <label class="block text-gray-700">Nama:</label>
                <input type="text" name="nama"
                    class="w-full border border-indigo-500 rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                    required>

                <!-- Kecamatan -->
                <label for="kecamatan">Kecamatan:</label>
                <select id="kecamatan" name="kecamatan" onchange="updateKelurahan()"
                    class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
                    {{-- <option value="">-- Pilih Kecamatan --</option> --}}
                    <option value="">Pilih Kecamatan</option>
                    @foreach(array_keys($data) as $kecamatan)
                        <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                    @endforeach
                </select>

                <!-- Kelurahan (tergantung Kecamatan) -->
                <label for="kelurahan">Kelurahan:</label>
                <select id="kelurahan" name="kelurahan"
                    class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Pilih Kelurahan</option>
                </select>
                {{-- alamat detial --}}
                <label class="block text-gray-700">Alamat:</label>
                <input type="text" name="alamat"
                    class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">

                <label class="block text-gray-700">Nomor Hanphone</label>
                <input type="number" name="nomor_handphone"
                    class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">

                <label class="block text-gray-700">Tanggal Pengaduan</label>
                <input type="date" name="date"
                    class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300">


            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Jenis Aduan:</label>
                <select name="jenis_aduan" id="jenis_aduan"
                    class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300" required>

                    <optgroup label="Pemeriksaan dan Instalasi">
                        <option value="pemeriksaan inst. & meter air">pemeriksaan inst. & meter air</option>
                        <option value="pemasangan instalasi air">pemasangan instalasi air</option>
                        <option value="pemasangan instalasasi air baru">pemasangan instalasasi air baru</option>
                        <option value="pemutusan instalasi">pemutusan instalasi</option>
                        <option value="pemutusan sementara">pemutusan sementara</option>
                        <option value="putus sementara Krn Meter hilang">putus sementara Krn Meter hilang</option>
                        <option value="ganti meter">ganti meter</option>
                        <option value="dispensasi pejabat">dispensasi pejabat</option>
                    </optgroup>

                    <optgroup label="Masalah Air & Meter">
                        <option value="meter kotor/ berembun">meter kotor/ berembun</option>
                        <option value="meter rusak">meter rusak</option>
                        <option value="meter hilang">meter hilang</option>
                        <option value="meter segel">meter segel</option>
                        <option value="meter terjept">meter terjept</option>
                        <option value="air kecil/tidak mengalir">air kecil/tidak mengalir</option>
                        <option value="air kotor">air kotor</option>
                        <option value="kran bocor/rusak">kran bocor/rusak</option>
                        <option value="stop keran bocor">stop keran bocor</option>
                        <option value="persil bocor">persil bocor</option>
                    </optgroup>

                    <optgroup label="Masalah Akses">
                        <option value="box terkunci">box terkunci</option>
                        <option value="box terkunci(tafsir)">box terkunci(tafsir)</option>
                        <option value="rumah kosong">rumah kosong</option>
                        <option value="rumah kosong(tafsiir)">rumah kosong(tafsiir)</option>
                        <option value="rumah tutup">rumah tutup</option>
                        <option value="rumah tutup(tafsir)">rumah tutup(tafsir)</option>
                    </optgroup>

                    <optgroup label="Pembacaan Meter">
                        <option value="baca ulang">baca ulang</option>
                        <option value="stand meter tertukar">stand meter tertukar</option>
                        <option value="stang meter mundur">stang meter mundur</option>
                        <option value="stand meter jalan terus">stand meter jalan terus</option>
                        <option value="stand meter tetap">stand meter tetap</option>
                        <option value="pakai rata-rata">pakai rata-rata</option>
                        <option value="stand melonjak">stand melonjak</option>
                        <option value="angka sesuai dipapan">angka sesuai dipapan</option>
                        <option value="koreksi stand meter">koreksi stand meter</option>
                        <option value="stand meter diatur">stand meter diatur</option>
                    </optgroup>

                    <optgroup label="Lainnya">
                        <option value="kopling putus/bocor">kopling putus/bocor</option>
                    </optgroup>

                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">isi Aduan</label>
                <textarea name="isi_aduan"
                    class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300"></textarea>
            </div>

            <button type="submit" id="submitBtn"
                class="bg-blue-700 text-white font-bold py-3 px-6 w-full rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-500">
                <span id="btnText">Kirim Pengaduan</span>
                <span id="loadingSpinner" class="hidden">Loading...</span>
            </button>
        </form>
    </div>

    {{--
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    <script>
        const modal = document.getElementById('tatacaraModal');

        function openModal() {
            modal.classList.remove('pointer-events-none', 'opacity-0', 'scale-95');
            modal.classList.add('opacity-100', 'scale-100');
        }

        function closeModal() {
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-95');
            // Tunggu transisi selesai lalu hilangkan pointer events
            setTimeout(() => {
                modal.classList.add('pointer-events-none');
            }, 300); // waktu harus sama dengan duration-300
        }

        // Auto show modal saat halaman load (opsional)
        window.addEventListener('load', openModal);
        document.querySelector('form').addEventListener('submit', function () {
            document.getElementById('btnText').classList.add('hidden');
            document.getElementById('loadingSpinner').classList.remove('hidden');
        });
        // Definisikan data dan fungsi di level global
        const wilayahData = @json($data);

        function updateKelurahan() {
            const kecamatanSelect = document.getElementById('kecamatan');
            const kelurahanSelect = document.getElementById('kelurahan');
            const selectedKecamatan = kecamatanSelect.value;

            // Reset kelurahan
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelurahanSelect.disabled = !selectedKecamatan;

            // Isi kelurahan jika kecamatan dipilih
            if (selectedKecamatan && wilayahData[selectedKecamatan]) {
                wilayahData[selectedKecamatan].forEach(kel => {
                    const option = document.createElement('option');
                    option.value = kel;
                    option.textContent = kel;
                    kelurahanSelect.appendChild(option);
                });
            }
        }

        function toggleNomorSaluran() {
            const jenis = document.getElementById('jenis_pelapor').value;
            const nomorSaluranField = document.getElementById('nomorSaluranField');

            if (jenis === 'personal') {
                nomorSaluranField.style.display = 'block';
            } else {
                nomorSaluranField.style.display = 'none';
            }
        }

        // Panggil saat halaman dimuat (kalau data lama diisi)
        document.addEventListener("DOMContentLoaded", toggleNomorSaluran);

        // Inisialisasi event listener
        document.getElementById('kecamatan').addEventListener('change', updateKelurahan);




    </script>


@endsection