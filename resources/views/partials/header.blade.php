<header class="h-16 flex items-center justify-between px-6 z-10">
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="relative hidden sm:block">
            eGift table
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
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-2 hover:bg-gray-50 rounded-lg p-1 transition-colors">
                <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                    alt="User" class="w-8 h-8 rounded-full">
                <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden sm:block transition-transform duration-200"
                    :class="{'rotate-180': open}"></i>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 origin-top-right"
                style="display: none;">
                <div class="px-4 py-3 border-b border-gray-50">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
                    <i class="fa-regular fa-user w-5"></i> View Profile
                </a>
                <a href="{{ route('appearance.edit') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
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