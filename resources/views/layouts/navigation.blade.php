<div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-[320px] bg-gray-900 text-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:block flex flex-col justify-between">
    <!-- Bagian atas -->
    <div>
        <div class="mb-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold tracking-wide text-white">Admin Panel</h1>
                <i class="bi bi-x cursor-pointer text-gray-400 hover:text-white lg:hidden" onclick="toggleSidebar()"></i>
            </div>
            <hr class="my-4 border-gray-700">
        </div>

        <nav class="space-y-1 px-4">
            <x-sidebar-link route="admin.dashboard" icon="bi-house-door-fill" label="Dashboard" />
            <x-sidebar-link route="admin.facilities.index" icon="bi-building" label="Facilities" match="admin.facilities.*" />
            <x-sidebar-link route="admin.background-videos.index" icon="bi-camera-video" label="Background Video" match="admin.background-video.*" />
            </nav>

        <hr class="my-6 border-gray-700 mx-4">

        <a href="{{ route('home') }}" target="_blank" class="flex items-center px-4 py-3 rounded-md text-sm font-medium hover:bg-blue-600 transition">
            <i class="bi bi-globe text-lg"></i>
            <span class="ml-3">View Site</span>
        </a>
    </div>

    <!-- Bagian bawah yang selalu mentok di bawah -->
    <div class="p-4 bg-gray-900 border-t border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center font-semibold text-white">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="text-sm">
                <p class="font-medium">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-gray-400 text-xs">{{ Auth::user()->email ?? 'email@example.com' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="flex items-center w-full p-3 rounded-md hover:bg-red-600 text-left transition">
                <i class="bi bi-box-arrow-right text-lg"></i>
                <span class="ml-3 font-medium">Logout</span>
            </button>
        </form>
    </div>
</div>
