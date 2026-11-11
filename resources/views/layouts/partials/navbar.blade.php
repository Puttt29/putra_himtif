<header class="sticky top-0 z-20 bg-surface/90 backdrop-blur border-b border-black/5 px-6 py-4">
    <div class="flex justify-between items-start">

        <!-- LEFT -->
        <div class="flex flex-col gap-1">

            <!-- Top Row -->
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden text-xl focus:outline-none">
                    ☰
                </button>

                <h1 class="font-semibold text-lg">
                    Web Admin Panel
                </h1>
            </div>

            <!-- Breadcrumb -->
            <nav class="text-sm text-textmuted">
                <ol class="flex items-center flex-wrap gap-1">

                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="hover:text-accent transition">
                            Dashboard
                        </a>
                    </li>

                    @foreach (request()->segments() as $segment)
                        @if (!is_numeric($segment))
                            <li class="mx-1">/</li>
                            <li class="capitalize text-textmain">
                                {{ $breadcrumbMap[$segment] ?? str_replace('-', ' ', $segment) }}
                            </li>
                        @endif
                    @endforeach

                </ol>
            </nav>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-4">

            <!-- Profile -->
            <div class="relative">
                <button onclick="toggleProfileDropdown()"
                    class="w-9 h-9 rounded-full bg-brandDark flex items-center justify-center focus:outline-none ring-2 ring-transparent hover:ring-accent transition">
                    <span class="text-sm font-semibold text-textmain">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </button>

                <!-- Dropdown -->
                <div id="profileDropdown"
                    class="hidden absolute right-0 mt-3 w-52 bg-surface rounded-xl shadow-xl border border-black/5 overflow-hidden z-50">

                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-black/5">
                        <p class="text-sm font-semibold">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-textmuted">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <!-- Menu -->
                    <div class="py-2 text-sm">
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('users.index') }}"
                                class="flex items-center gap-3 px-4 py-2 hover:bg-base transition">
                                User Management
                            </a>
                        @endif
                    </div>

                    <div class="py-2 text-sm">
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('angkatan.index') }}"
                                class="flex items-center gap-3 px-4 py-2 hover:bg-base transition">
                                Angkatan Management
                            </a>
                        @endif
                    </div>

                    <!-- Logout -->
                    <div class="border-t border-black/5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 transition text-sm text-left">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
