@extends('index')

@section('content')

    <div>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">User Accounts</h1>
                <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Add New User
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Admins --}}
            <section class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Admins <span class="text-sm text-gray-500">({{ $admins->total() ?? $admins->count() }})</span></h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @forelse($admins as $user)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-100 relative">
                            @if($user->roleInfo)
                                <span class="absolute top-4 right-4 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    {{ strtoupper($user->roleInfo->role_name) }}
                                </span>
                            @endif
                            <div class="p-5">
                                <div class="flex items-center gap-4 mb-5">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-white text-xl font-bold bg-green-500">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $user->username }}</h3>
                                </div>

                                <div class="space-y-3 mb-5">
                                    @if(isset($user->contact))
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 text-gray-400">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                        </svg>
                                        <span>{{ $user->contact }}</span>
                                    </div>
                                    @endif

                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 text-gray-400">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                                        </svg>
                                        <span>Joined: {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-4 flex items-center justify-between gap-3">
                                    <a href="{{ route('users.edit', $user->user_id) }}" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    @if($user->user_id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->user_id) }}" 
                                              method="POST" 
                                              class="flex-1" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500">No admins found.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $admins->links() }}
                </div>
            </section>

            {{-- Staff --}}
            <section class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Staff <span class="text-sm text-gray-500">({{ $staffs->total() ?? $staffs->count() }})</span></h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @forelse($staffs as $user)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-100 relative">
                            @if($user->roleInfo)
                                <span class="absolute top-4 right-4 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                    {{ strtoupper($user->roleInfo->role_name) }}
                                </span>
                            @endif
                            <div class="p-5">
                                <div class="flex items-center gap-4 mb-5">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-white text-xl font-bold bg-blue-500">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $user->username }}</h3>
                                </div>

                                <div class="space-y-3 mb-5">
                                    @if(isset($user->contact))
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 text-gray-400">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                        </svg>
                                        <span>{{ $user->contact }}</span>
                                    </div>
                                    @endif

                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 text-gray-400">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                                        </svg>
                                        <span>Joined: {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-4 flex items-center justify-between gap-3">
                                    <a href="{{ route('users.edit', $user->user_id) }}" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    @if($user->user_id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->user_id) }}" 
                                              method="POST" 
                                              class="flex-1" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500">No staff found.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $staffs->links() }}
                </div>
            </section>


    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const mainContent = document.getElementById('mainContent');
        
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
            mainContent.classList.remove('ml-64');
            mainContent.classList.add('ml-16');
        }
        
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-16');
            } else {
                mainContent.classList.remove('ml-16');
                mainContent.classList.add('ml-64');
            }
            
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        });
    </script>
    </div>
@endsection