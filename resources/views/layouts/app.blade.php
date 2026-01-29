<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8" />
    <title>@yield('title')</title>
    @vite(['resources/css/app.css'])
    <style>
        /* Custom styles for better mobile experience */
        @media (max-width: 1023px) {
            #menu {
                transition: all 0.3s ease;
                z-index: 1000;
            }

            #menu li {
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            #menu li:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-primary transition-colors duration-300">
    <!-- Navbar -->
    <nav class="bg-gray-100 p-4 shadow-md sticky top-0 z-50 transation-colors duration-300 dark:bg-gray-800">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/LOGO_PDAM2.png') }}" alt="Logo PDAM" class="h-16 md:h-20">
            </a>

            <!-- Hamburger Button -->
            <button id="menu-toggle" class="lg:hidden text-black focus:outline-none" aria-label="Toggle menu">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>

            <!-- Menu -->
            <ul id="menu" class="hidden lg:flex space-x-6 items-center">
                <li><a href="{{ route('complaint.create') }}"
                        class="text-black dark:text-gray-200 hover:underline text-lg font-medium transition duration-200 hover:text-blue-100 ">Buat
                        Pengaduan</a></li>
                <li><a href="{{ route('complaint.checkStatusForm') }}"
                        class="text-black dark:text-gray-200 hover:underline text-lg font-medium transition duration-200 hover:text-blue-100 ">Cek
                        Status</a></li>

                <button id="theme-toggle" class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700
           text-gray-800 dark:text-gray-100
           hover:scale-105 transition">
                    🌙
                </button>

            </ul>
        </div>

        <!-- Mobile Menu (shown below navbar) -->
        <div id="mobile-menu" class="hidden lg:hidden bg-blue-600">
            <ul class="flex flex-col">
                <li class="border-b border-blue-700"><a href="{{ route('complaint.create') }}"
                        class="block py-3 px-4 text-black hover:bg-blue-700 transition duration-200 dark:text-white">Buat
                        Pengaduan</a>
                </li>
                <li><a href="{{ route('complaint.checkStatusForm') }}"
                        class="block py-3 px-4 text-black hover:bg-blue-700 transition duration-200 dark:text-white">Cek
                        Status</a></li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <main class=" flex-auto container mx-auto px-0 py-6 md:px-6 lg:px-2 flex justify-center">
        @yield('content')
    </main>
    @include('partials.footer')
    @include('sweetalert::alert')


    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');

            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
        });

        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
                document.getElementById('menu-toggle').setAttribute('aria-expanded', 'false');
            });
        });

        const html = document.documentElement;
        const toggle = document.getElementById('theme-toggle');

        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            html.classList.add('dark');
            toggle.textContent = '☀️';
        } else {
            toggle.textContent = '🌙';
        }

        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');

            if (html.classList.contains('dark')) {
                localStorage.theme = 'dark';
                toggle.textContent = '☀️';
            } else {
                localStorage.theme = 'light';
                toggle.textContent = '🌙';
            }
        });
    </script>

    @if(session()->has('swal_msg'))
    @endif

</body>

</html>