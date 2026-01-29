@vite('resources/css/app.css')
<script src="https://cdn.tailwindcss.com"></script>
<title>Daftar Pengaduan</title>
@php
    $unreadCount = \App\Models\Complaint::where('status', 'pending')->count();
@endphp
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <header class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Dashboard Pengaduan</h1>
                    <p class="text-gray-600">Kelola semua pengaduan masyarakat</p>
                </div>

                <div class="flex space-x-4">
                    <a href="{{ route('password.change') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Ganti Password
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 rounded-lg shadow-sm text-white hover:bg-red-700 transition-colors duration-200 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.filter', ['status' => 'pending']) }}"
                    class="relative inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd" />
                    </svg>
                    Daftar Pengaduan
                    @if($unreadCount > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            </div>
        </header>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Filter Pengaduan</h2>

            <!-- Search and Status Filter -->
            <form method="GET" autocomplete="off" action="{{ route('admin.filter') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pengaduan</label>
                        <input type="text" name="search" id="search" placeholder="ADU-YYYY-0000"
                            value="{{ request('search') }}"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Semua Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="bulan" id="bulan"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="tahun" id="tahun"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Semua Tahun</option>
                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-3">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        Cari Pengaduan
                    </button>
                    <a href="{{ route('admin.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Date Filter -->
            <form method="GET" action="{{ route('admin.filter') }}">
                <div class="flex flex-col md:flex-row items-end space-y-3 md:space-y-0 md:space-x-4">
                    <div class="flex-1">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan
                            Tanggal</label>
                        <div class="flex">
                            <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                                class="flex-1 p-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
                            <button type="submit"
                                class="px-4 py-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition-colors duration-200">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- filter sesuai jenis aduan --}}
            <form action="{{ route('admin.filter') }}" method="GET" class="mb-4">
                <select name="kategori" class="border p-2 rounded">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori_aduan as $key => $value)
                        <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                            {{ $key }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
            </form>


            {{-- fungsi untuk donwload data --}}
            <form method="GET" class="flex flex-wrap items-end gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal:</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="border rounded p-2 w-full"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal:</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="border rounded p-2 w-full" required>
                </div>

                <div class="flex gap-3">
                    {{-- <button type="submit" formtarget="_blank" formaction="{{ route('admin.complaint.exportPDF') }}"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Export PDF
                    </button> --}}

                    <div>
                        <button type="submit" formtarget="_blank" formaction="{{ route('admin.complaint.export') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Export Excel
                        </button>
                    </div>
                </div>
            </form>
            <div>
                <form action="{{ route('admin.export.jenis') }}" method="GET">
                    <label for="tahun">Pilih Tahun:</label>
                    <select name="tahun" id="tahun">
                        @for ($i = now()->year; $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit">Export PDF</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const kategoriData = @json($kategori_aduan);
    const kategoriSelect = document.getElementById('kategori');
    const jenisSelect = document.getElementById('jenis_aduan');

    kategoriSelect.addEventListener('change', function () {
        const selectedKategori = this.value;

        // Kosongkan dropdown jenis aduan
        jenisSelect.innerHTML = '<option value="">Semua Jenis Aduan</option>';

        if (kategoriData[selectedKategori]) {
            kategoriData[selectedKategori].forEach(function (jenis) {
                const option = document.createElement('option');
                option.value = jenis;
                option.textContent = jenis;
                jenisSelect.appendChild(option);
            });
        }
    });
</script>