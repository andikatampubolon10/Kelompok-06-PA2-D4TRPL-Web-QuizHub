<!-- resources/views/layouts/operator-sidebar.blade.php -->

<div class="space-y-6">
    <div class="mb-6">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Menu Utama</h3>
    </div>

    <nav class="space-y-1">
        <a class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-300 ease-in-out"
            href="{{ route('Guru.Course.index') }}">
            <div
                class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors ">
                <i class="fas fa-user text-black mr-2"></i>
            </div>
            <span class="text-sm font-medium">Mata Pelajaran</span>
        </a>

        <a class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-300 ease-in-out {{ request()->routeIs('Operator.Kurikulum.*') ? 'bg-green-100 text-green-700 border-l-4 border-green-600 shadow-md' : '' }}"
            href="{{ route('Guru.Latihan.index') }}">
            <div
                class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors {{ request()->routeIs('Operator.Kurikulum.*') ? 'bg-green-200' : '' }}">
                <i class="fas fa-briefcase text-black mr-2"></i>
            </div>
            <span class="text-sm font-medium">Latihan Soal</span>
        </a>

           <a class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-300 ease-in-out {{ request()->routeIs('Operator.Kurikulum.*') ? 'bg-green-100 text-green-700 border-l-4 border-green-600 shadow-md' : '' }}"
            href="{{ route('Guru.Nilai.index') }}">
            <div
                class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors {{ request()->routeIs('Operator.Kurikulum.*') ? 'bg-green-200' : '' }}">
                <i class="fas fa-briefcase text-black mr-2"></i>
            </div>
            <span class="text-sm font-medium">Nilai</span>
        </a>
    </nav>

    <!-- Logout Button (Placed at the bottom) -->
    <div class="pt-6 mt-6 border-t border-gray-200">
        <form action="{{ route('logout') }}" method="POST"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200 ease-in-out">
            @csrf
            <button type="submit" class="flex items-center justify-start w-full bg-transparent border-0">
                <div
                    class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-lg mr-3 group-hover:bg-red-200 transition-colors">
                    <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                </div>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>
</div>
