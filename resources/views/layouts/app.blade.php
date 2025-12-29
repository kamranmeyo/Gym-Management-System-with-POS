<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', '🏋️ C4') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:block">
            <div class="p-4 text-center font-bold text-xl border-b border-gray-700">
                🏋️‍♂️ C4 Gym
            </div>

            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" 
                   class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-800' : '' }}">
                    📊 Dashboard
                </a>


<!-- Sidebar Menu -->
<div x-data="{ open: false }" class="mb-2">
    <!-- Parent Menu Button -->
    <button 
        @click="open = !open"
        class="w-full flex justify-between items-center py-2.5 px-4 hover:bg-gray-700 text-left text-white"
    >
        <span>💵 Fee</span>
        <!-- Down Arrow Icon -->
        <svg :class="{ 'rotate-180': open }" 
             class="w-4 h-4 transform transition-transform duration-200"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Submenu (hidden by default) -->
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1" x-cloak>
                <a href="{{ route('fee.index') }}" 
                   class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('fee.index') ? 'bg-gray-800' : '' }}">
                    💵 Submit Fee
                </a>

                <a href="{{ route('fee.report') }}" 
                    class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('fee.report') ? 'bg-gray-800' : '' }}">
                    📑 Fee Reports
                </a>
    </div>
</div>




<!-- Sidebar Menu -->
<div x-data="{ open: false }" class="mb-2">
    <!-- Parent Menu Button -->
    <button 
        @click="open = !open"
        class="w-full flex justify-between items-center py-2.5 px-4 hover:bg-gray-700 text-left text-white"
    >
        <span>👥 Member</span>
        <!-- Down Arrow Icon -->
        <svg :class="{ 'rotate-180': open }" 
             class="w-4 h-4 transform transition-transform duration-200"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Submenu (hidden by default) -->
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1" x-cloak>
                <a href="{{ route('members.create') }}" 
                   class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('members.create') ? 'bg-gray-800' : '' }}">
                    ➕ Add Member
                </a>


                <a href="{{ route('members.index') }}" 
                   class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('members.index') ? 'bg-gray-800' : '' }}">
                    👥 Members List
                </a>
    </div>
</div>

<!-- Load Alpine.js (once in your layout, usually in <head> or before </body>) -->
<script src="https://unpkg.com/alpinejs" defer></script>

<!-- Sidebar Menu -->
<div x-data="{ open: false }" class="mb-2">
    <!-- Parent Menu Button -->
    <button 
        @click="open = !open"
        class="w-full flex justify-between items-center py-2.5 px-4 hover:bg-gray-700 text-left text-white"
    >
        <span>📦 Plan</span>
        <!-- Down Arrow Icon -->
        <svg :class="{ 'rotate-180': open }" 
             class="w-4 h-4 transform transition-transform duration-200"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Submenu (hidden by default) -->
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1" x-cloak>
        <a href="{{ route('plans.create') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('plans.create') ? 'bg-gray-800' : '' }}">
            📝 Add Plan
        </a>

        <a href="{{ route('plans.index') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('plans.index') ? 'bg-gray-800' : '' }}">
            📋 List Plans
        </a>
    </div>
</div>



<!-- Sidebar Menu -->
<div x-data="{ open: false }" class="mb-2">
    <!-- Parent Menu Button -->
    <button 
        @click="open = !open"
        class="w-full flex justify-between items-center py-2.5 px-4 hover:bg-gray-700 text-left text-white"
    >
        <span>🕒 Attendance</span>
        <!-- Down Arrow Icon -->
        <svg :class="{ 'rotate-180': open }" 
             class="w-4 h-4 transform transition-transform duration-200"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Submenu (hidden by default) -->
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1" x-cloak>
        <a href="{{ route('attendance.index') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('attendance.index') ? 'bg-gray-800' : '' }}">
            ✅️ Mark Attendance
        </a>

        <a href="{{ route('attendance.list') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('attendance.list') ? 'bg-gray-800' : '' }}">
            👁️ View Attendance
        </a>
    </div>
</div>







                {{-- <a href="{{ route('attendance.index') }}"  
                   class="block py-2.5 px-4 hover:bg-gray-700 {{ request()->routeIs('attendance.index') ? 'bg-gray-800' : '' }}">
                    🕒 Mark Attendance
                </a> --}}










<!-- Sidebar Menu POS -->
<div x-data="{ open: false }" class="mb-2">
    <!-- Parent Menu Button -->
    <button 
        @click="open = !open"
        class="w-full flex justify-between items-center py-2.5 px-4 hover:bg-gray-700 text-left text-white"
    >
        <span>🛒 POS</span>
        <!-- Down Arrow Icon -->
        <svg :class="{ 'rotate-180': open }" 
             class="w-4 h-4 transform transition-transform duration-200"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Submenu (hidden by default) -->
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1" x-cloak>

        <a href="{{ route('pos.sales.index') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('pos.sales.index') ? 'bg-gray-800' : '' }}">
            📝 Sales
        </a>

        <a href="{{ route('pos.products.index') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('pos.products.index') ? 'bg-gray-800' : '' }}">
            📋 Products
        </a>
    </div>
</div>



<!-- Sidebar Menu Expense-->
<div x-data="{ open: false }" class="mb-2">
    <!-- Parent Menu Button -->
    <button 
        @click="open = !open"
        class="w-full flex justify-between items-center py-2.5 px-4 hover:bg-gray-700 text-left text-white"
    >
        <span>💰 Expense</span>
        <!-- Down Arrow Icon -->
        <svg :class="{ 'rotate-180': open }" 
             class="w-4 h-4 transform transition-transform duration-200"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Submenu (hidden by default) -->
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1" x-cloak>

        <a href="{{ route('expenses.index') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('expenses.index') ? 'bg-gray-800' : '' }}">
            📝 List
        </a>

        <a href="{{ route('expenses.report') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('expenses.report') ? 'bg-gray-800' : '' }}">
            📋 Report
        </a>
                <a href="{{ route('expense-categories.index') }}" 
           class="block py-2.5 px-4 hover:bg-gray-700 rounded {{ request()->routeIs('expense-categories.index') ? 'bg-gray-800' : '' }}">
            📂 Categories
        </a>
    </div>
</div>








            </nav>

            <div class="absolute bottom-0 w-full border-t border-gray-700 p-4 text-center text-sm text-gray-400">
                <b style="color: black">Software Made by 0301-6228258</b>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <button id="menu-toggle" class="md:hidden text-gray-600">
                    ☰
                </button>
                <h1 class="text-xl font-semibold text-gray-700">
                    {{ $title ?? 'Dashboard' }}
                </h1>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-500 hover:text-red-700">Logout</button>
                </form>
            </header>

            <!-- Content -->
            <main class="p-6 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle Script -->
    <script>
        const toggle = document.getElementById('menu-toggle');
        const sidebar = document.querySelector('aside');

        toggle?.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- Export Buttons --}}
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>




</body>
</html>
