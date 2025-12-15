<header class="h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="relative hidden sm:block">
            <div class="bg-white shadow-sm p-1 rounded-xl flex items-center">
                <i class="fa-solid fa-magnifying-glass text-gray-400 ml-3"></i>
                <input type="text" placeholder="Search..."
                    class="pl-3 pr-4 py-1.5 bg-transparent border-none text-sm focus:ring-0 w-64">
                <span class="text-xs text-gray-400 border border-gray-200 rounded px-1.5 py-0.5 mr-1">⌘
                    K</span>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button onclick="toggleNotificationDrawer()"
            class="p-2 text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-colors relative">
            <i class="fa-regular fa-bell"></i>
            <span id="notificationBadge"
                class="hidden absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            <span id="notificationBadgeCount"
                class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"></span>
        </button>
        <button class="p-2 text-gray-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
            <i class="fa-regular fa-calendar"></i>
        </button>
        <div class="h-8 w-px bg-gray-200 mx-2"></div>

        <!-- Profile Dropdown -->
        <div class="relative">
            <button id="profileBtn" onclick="togglePopup('profileDropdown')"
                class="flex items-center gap-2 hover:bg-gray-50 rounded-lg p-1 transition-colors">
                <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                    alt="User" class="w-8 h-8 rounded-full">
                <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden sm:block"></i>
            </button>

            <div id="profileDropdown"
                class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 popup-content">
                <div class="px-4 py-3 border-b border-gray-50">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
                    <i class="fa-regular fa-user w-5"></i> View Profile
                </a>
                <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
                    <i class="fa-solid fa-gear w-5"></i> Settings
                </a>
                <div class="border-t border-gray-50 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>