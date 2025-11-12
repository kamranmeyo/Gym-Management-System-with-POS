<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">🏋️ Gym Dashboard - {{ Auth::user()->name }}</h1>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            
            <!-- Active Members -->
            <a href="{{ route('members.index') }}">
            <div class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Active Members</h3>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $activeMembers }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <i class="fa-solid fa-users text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </a>
            <!-- Add New Members -->
            <a href="{{ route('members.create') }}" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Add New Member</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">+</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fa-solid fa-user-plus text-green-600 text-xl"></i>
                    </div>
                </div>
            </a>

            <!-- Pending Renewals -->
            <div class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Pending Renewals</h3>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $pendingRenewals }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fa-solid fa-hourglass-half text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Visits (Today) -->
            <div class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Total Visits (Today)</h3>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{$attendanceToday}}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fa-solid fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Reports -->
            <a href="{{ route('attendance.index') }}" class="bg-white p-5 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm text-gray-500">Start Scanning</h3>
                        <p class="text-3xl font-bold text-yellow-600 mt-2"><i class="fa-solid fa-chart-line"></i></p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fa-solid fa-file-lines text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </a>
        </div>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a2d9d6a64a.js" crossorigin="anonymous"></script>
</x-app-layout>
