@vite('resources/css/app.css')
<script src="https://cdn.tailwindcss.com"></script>
<title>Registrasi Admin</title>
<h2 class="text-center text-2xl bold my-4">Registrasi Admin</h2>
<div class="container mx-auto mt-6 p-4 flex justify-center">

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST"
        class="w-full max-w-xs bg-white py-5 px-8 rounded-lg shadow-lg">
        @csrf
        <div class="mb-3">
            <label class="block text-gray-700 text-lg" for="name">Nama:</label>
            <input
                class="w-full border border-indigo-500 rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                type="text" name="name" id="name" required value="{{ old('name') }}">

            <label class="block text-gray-700 text-lg" for="email">Email:</label>
            <input
                class="w-full border border-indigo-500 rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                type="email" name="email" id="email" required value="{{ old('email') }}">

            <label class="block text-gray-700 text-lg" for="password">Password:</label>
            <input
                class="w-full border border-indigo-500 rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                type="password" name="password" id="password" required>

            <label class="block text-gray-700 text-lg" for="password_confirmation">Konfirmasi Password:</label>
            <input
                class="w-full border border-indigo-500 rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                type="password" name="password_confirmation" id="password_confirmation" required>
            <button
                class="text-center block bg-blue-500 text-white font-bold py-3 px-6 w-full rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-600 "
                type="submit">Daftar</button>
    </form>

    <p class="mt-4 text-2xl text-black">
        Sudah punya akun? <a
            class="text-center block bg-blue-500 text-white font-bold py-3 px-5 w-full rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:bg-blue-600"
            href="{{ route('login') }}">Login</a>
    </p>
</div>
</div>