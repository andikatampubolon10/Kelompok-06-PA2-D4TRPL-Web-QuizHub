@extends('layouts.operator-layout')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')
@section('page-description', 'Kelola informasi tahun ajaran sekolah')

@section('content')
    <div class="space-y-6">
        {{-- @dd($kurikulum) --}}
        <pre>{{ session('success') }}</pre>
        <nav
            class="flex items-center space-x-2 text-sm text-gray-600 bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-100">
            <a href="{{ route('Operator.Kurikulum.index') }}"
                class="text-green-600 hover:text-green-700 font-medium transition-colors">
                <i class="fas fa-book mr-1"></i>
                Kurikulum
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-800 font-medium">Tahun Ajaran</span>
        </nav>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Tahun Ajaran</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($tahunAjaran) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Status Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $tahunAjaran->filter(function ($tahun) {return now()->between($tahun->Mulai_Tahun_Ajaran, $tahun->Selesai_Tahun_Ajaran);})->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tahun Berjalan</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ date('Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Tahun Ajaran</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola dan atur tahun ajaran sekolah</p>
                    </div>
                    <!-- Fixed route to use parameter instead of query string -->
                    <a href="{{ route('Operator.TahunAjaran.create', ['id_kurikulum' => $kurikulum->id_kurikulum]) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Tahun Ajaran
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if (count($tahunAjaran) > 0)
                    <div class="space-y-4">
                        @foreach ($tahunAjaran as $tahun)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <a
                                            href="{{ route('Operator.semester.index', ['id_tahun_ajaran' => $tahun->ID_Tahun_Ajaran]) }}">
                                            <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                                {{-- @dd($tahun->ID_Tahun_Ajaran) --}}
                                                {{ $tahun->Nama_Tahun_Ajaran }}
                                            </h4>
                                        </a>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                Mulai:
                                                {{ \Carbon\Carbon::parse($tahun->Mulai_Tahun_Ajaran)->format('d M Y') }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar-check mr-2"></i>
                                                Selesai:
                                                {{ \Carbon\Carbon::parse($tahun->Selesai_Tahun_Ajaran)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if (now()->between($tahun->Mulai_Tahun_Ajaran, $tahun->Selesai_Tahun_Ajaran))
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Tidak Aktif
                                            </span>
                                        @endif
                                        <a href="{{ route('Operator.TahunAjaran.edit', [
                                            'id_kurikulum' => $kurikulum->id_kurikulum,
                                            'id_tahun_ajaran' => $tahun->ID_Tahun_Ajaran,
                                        ]) }}"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada tahun ajaran</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan tahun ajaran pertama Anda.</p>
                        <!-- Fixed route to use parameter instead of query string -->
                        <a href="{{ route('Operator.TahunAjaran.create', ['id_kurikulum' => $kurikulum->id_kurikulum]) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Tahun Ajaran
                        </a>

                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    @if (session('success'))
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                            <p class="text-sm text-gray-600">{{ session('success') }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button onclick="closeModal()"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function closeModal() {
                document.getElementById('successModal').style.display = 'none';
            }

            // Auto close modal after 3 seconds
            setTimeout(closeModal, 3000);
        </script>
    @endif

@endsection
