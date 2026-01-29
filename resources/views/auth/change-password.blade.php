@vite(['resources/css/app.css'])
<script src="https://cdn.tailwindcss.com"></script>
<div class="max-w-md mx-auto mt-8 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Ganti Password</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Password Lama</label>
            <input type="password" name="current_password" required id="oldpass" placeholder="*****"
                class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Password Baru</label>
            <input type="password" name="new_password" required id="newpass" placeholder="*****"
                class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" required id="conpass" placeholder="*****"
                class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Ubah Password
        </button>
    </form>
    <input type="checkbox" onclick="openPass()" class=" text-gray-700 my-2"> show
    <div class="mt-6 text-center">
        <a href="{{ route('admin.index') }}" class="text-blue-500 hover:underline">
            ← Kembali ke daftar
        </a>
    </div>
</div>


<script>
    function openPass() {
        const fields = ['newpass', 'oldpass', 'conpass'];
        fields.forEach(id => {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    }
</script>