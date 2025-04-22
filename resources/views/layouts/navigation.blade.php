<div class="sidebar fixed top-0 bottom-0 lg:left-0 p-2 w-[300px] overflow-y-auto text-center bg-gray-900 shadow-lg lg:block hidden">
    <div class="text-gray-100 text-xl">
        <div class="p-2.5 mt-1 flex items-center">
            <h1 class="font-bold text-gray-200 text-xl ml-3">Admin Panel</h1>
            <i class="bi bi-x cursor-pointer ml-28 lg:hidden" onclick="openSidebar()"></i>
        </div>
        <div class="my-2 bg-gray-600 h-[1px]"></div>
    </div>
    
    <a href="{{ route('admin.dashboard') }}" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-blue-600' }} text-white">
        <i class="bi bi-house-door-fill"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Dashboard</span>
    </a>
    
    <a href="{{ route('admin.categories.index') }}" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600' : 'hover:bg-blue-600' }} text-white">
        <i class="bi bi-collection"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Categories</span>
    </a>
    
    <a href="{{ route('admin.menus.index') }}" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer {{ request()->routeIs('admin.menus.*') ? 'bg-blue-600' : 'hover:bg-blue-600' }} text-white">
        <i class="bi bi-menu-button"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Menu Items</span>
    </a>
    
    <a href="{{ route('admin.outlets.index') }}" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer {{ request()->routeIs('admin.outlets.*') ? 'bg-blue-600' : 'hover:bg-blue-600' }} text-white">
        <i class="bi bi-shop"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Outlets</span>
    </a>
    
    <a href="{{ route('admin.facilities.index') }}" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer {{ request()->routeIs('admin.facilities.*') ? 'bg-blue-600' : 'hover:bg-blue-600' }} text-white">
        <i class="bi bi-building"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Facilities</span>
    </a>
    
    <a href="{{ route('admin.careers.index') }}" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer {{ request()->routeIs('admin.careers.*') ? 'bg-blue-600' : 'hover:bg-blue-600' }} text-white">
        <i class="bi bi-briefcase"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Careers</span>
    </a>
    
    <div class="my-4 bg-gray-600 h-[1px]"></div>
    
    <a href="{{ route('home') }}" target="_blank" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
        <i class="bi bi-globe"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">View Site</span>
    </a>
    
    <div class="fixed bottom-0 left-0 w-[300px] p-2 bg-gray-900">
        <div class="my-2 bg-gray-600 h-[1px]"></div>
        <div class="flex items-center p-2.5">
            <div class="flex-shrink-0">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-700 text-white">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </span>
            </div>
            <div class="ml-3 text-left">
                <p class="text-sm font-medium text-gray-200">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-red-600 text-white">
                <i class="bi bi-box-arrow-right"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Logout</span>
            </button>
        </form>
    </div>
</div>