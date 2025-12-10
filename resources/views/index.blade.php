<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tejada Clinic</title>
    @vite('resources/css/app.css')
    <style>
        @yield("style")
    </style>
    @livewireStyles
</head>
<body class="tracking-wide bg-gray-50 text-gray-900">
    
    <!-- HEADER -->
    <header class="bg-white border-b border-gray-200 h-14 flex items-center justify-between px-4 fixed top-0 left-0 right-0 z-10 transition-all duration-300">
        <!-- Left: Toggle & Title -->
        <div class="flex items-center gap-4">
            <button id="toggleBtn" class="p-2 hover:bg-gray-100 rounded-lg text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h2 class="text-lg font-semibold text-gray-800">Tejada Dent</h2>
        </div>

        <!-- Right: Logout -->
        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-3 py-2 text-[#f56565] hover:bg-red-50 rounded-lg transition-all duration-300 group">  
                    <span class="flex items-center justify-center w-5 h-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                    </span>
                    <span class="hidden md:inline font-medium">Logout</span>
                </button>
            </form>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="peer bg-white border-r border-gray-200 fixed left-0 top-14 bottom-0 overflow-y-auto overflow-x-hidden transition-all duration-300 w-64 flex flex-col [&.collapsed]:w-16 group z-20">
        <nav class="w-full py-6 flex flex-col justify-between h-full">
            <ul class="space-y-2 w-full px-2">
                
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'bg-[#0086DA] text-white' : 'text-gray-600 hover:bg-gray-100' }} 
                              flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group-[.collapsed]:justify-center">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-300 group-[.collapsed]:w-0 group-[.collapsed]:opacity-0 font-medium">
                            Dashboard
                        </span>
                    </a>
                </li>

                <!-- Appointments -->
                <li>
                    <a href="{{ route('appointment') }}"
                       class="{{ request()->routeIs('appointment') ? 'bg-[#0086DA] text-white' : 'text-gray-600 hover:bg-gray-100' }} 
                              flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group-[.collapsed]:justify-center">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-clock"><path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h5"/><path d="M17.5 17.5 16 16.25V14"/><path d="M22 16a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/></svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-300 group-[.collapsed]:w-0 group-[.collapsed]:opacity-0 font-medium">
                            Appointments
                        </span>
                    </a>
                </li>

                <!-- Patients -->
                <li>
                    <a href="{{ route('patient-records') }}"
                       class="{{ request()->routeIs('patient-records') ? 'bg-[#0086DA] text-white' : 'text-gray-600 hover:bg-gray-100' }} 
                              flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group-[.collapsed]:justify-center">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-300 group-[.collapsed]:w-0 group-[.collapsed]:opacity-0 font-medium">
                            Patients
                        </span>
                    </a>
                </li>

                <!-- Reports -->
                <li>
                    <a href="{{ route('reports.index') }}"
                       class="{{ request()->routeIs('reports.*') ? 'bg-[#0086DA] text-white' : 'text-gray-600 hover:bg-gray-100' }} 
                              flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group-[.collapsed]:justify-center">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-chart-column"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 18v-1"/><path d="M12 18v-6"/><path d="M16 18v-3"/></svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-300 group-[.collapsed]:w-0 group-[.collapsed]:opacity-0 font-medium">
                            Reports
                        </span>
                    </a>
                </li>

                <!-- User Accounts -->
                <li>
                    <a href="{{ route('users.index') }}"
                       class="{{ request()->routeIs('users.*') ? 'bg-[#0086DA] text-white' : 'text-gray-600 hover:bg-gray-100' }} 
                              flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group-[.collapsed]:justify-center">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-300 group-[.collapsed]:w-0 group-[.collapsed]:opacity-0 font-medium">
                            User Accounts
                        </span>
                    </a>
                </li>
            </ul>

            <!-- Bottom: Backup (Optional utility from your routes) -->
            <ul class="space-y-2 w-full px-2 mb-4">
                 <li>
                    <a href="{{ route('admin.db.backup') }}"
                       class="text-gray-500 hover:bg-gray-100 hover:text-gray-800 flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group-[.collapsed]:justify-center">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database-backup"><path d="M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5Z"/><path d="M3 12h18"/><path d="M12 21v-4"/></svg>
                        </span>
                        <span class="whitespace-nowrap overflow-hidden transition-all duration-300 group-[.collapsed]:w-0 group-[.collapsed]:opacity-0 font-medium text-sm">
                            Backup Database
                        </span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="ml-64 transition-all duration-300 peer-[.collapsed]:ml-16 mt-14 p-6">
        @yield("content")
    </main>

    @stack('script')
    <script>
        (function(){
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');
            const mainContent = document.querySelector('main');

            if (!sidebar || !toggleBtn) return;

            // Restore state from local storage
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('collapsed');
            }
            
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                // Save state to local storage
                localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
            });
        })();
    </script>
    @livewireScripts
</body>
</html>