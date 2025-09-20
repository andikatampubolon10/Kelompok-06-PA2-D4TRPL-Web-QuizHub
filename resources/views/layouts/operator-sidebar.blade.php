<!-- resources/views/layouts/operator-sidebar.blade.php -->

<div class="space-y-6">
    <div class="mb-6">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Menu Utama</h3>
    </div>

    <nav class="space-y-1">
        <a class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200 {{ request()->routeIs('Operator.Kurikulum.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-500' : '' }}"
            href="{{ route('Operator.Kurikulum.index') }}">
            <div
                class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors {{ request()->routeIs('Operator.Kurikulum.*') ? 'bg-green-200' : '' }}">
                <i class="fas fa-book text-green-600 text-sm"></i>
            </div>
            <span>Kurikulum</span>
            @if (request()->routeIs('Operator.Kurikulum.*'))
                <i class="fas fa-chevron-right ml-auto text-xs text-green-500"></i>
            @endif
        </a>

        <a class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200 {{ request()->routeIs('Operator.Guru.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-500' : '' }}"
            href="{{ route('Operator.Guru.index') }}">
            <div
                class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors {{ request()->routeIs('Operator.Guru.*') ? 'bg-green-200' : '' }}">
                <i class="fas fa-chalkboard-teacher text-green-600 text-sm"></i>
            </div>
            <span>Guru</span>
            @if (request()->routeIs('Operator.Guru.*'))
                <i class="fas fa-chevron-right ml-auto text-xs text-green-500"></i>
            @endif
        </a>

        <a class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200 {{ request()->routeIs('Operator.Kelas.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-500' : '' }}"
            href="{{ route('Operator.Kelas.index') }}">
            <div
                class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors {{ request()->routeIs('Operator.Kelas.*') ? 'bg-green-200' : '' }}">
                <i class="fas fa-school text-green-600 text-sm"></i>
            </div>
            <span>Kelas</span>
            @if (request()->routeIs('Operator.Kelas.*'))
                <i class="fas fa-chevron-right ml-auto text-xs text-green-500"></i>
            @endif
        </a>

        <a class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200 {{ request()->routeIs('Operator.Siswa.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-500' : '' }}"
            href="{{ route('Operator.Siswa.index') }}">
            <div
                class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors {{ request()->routeIs('Operator.Siswa.*') ? 'bg-green-200' : '' }}">
                <i class="fas fa-user-graduate text-green-600 text-sm"></i>
            </div>
            <span>Siswa</span>
            @if (request()->routeIs('Operator.Siswa.*'))
                <i class="fas fa-chevron-right ml-auto text-xs text-green-500"></i>
            @endif
        </a>
    </nav>

    <!-- Logout Button (Placed at the bottom) -->
    <div class="pt-6 mt-6 border-t border-gray-200">
        <form action="{{ route('logout') }}" method="POST"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200">
            @csrf
            <button type="submit" class="flex items-center justify-start w-full bg-transparent border-0">
                <div
                    class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-lg mr-3 group-hover:bg-red-200 transition-colors">
                    <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                </div>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
