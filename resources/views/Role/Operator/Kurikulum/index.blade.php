@extends('layouts.operator-layout')

@section('title', 'Informasi Kurikulum')
@section('page-title', 'Kurikulum')
@section('page-description', 'Kelola informasi kurikulum sekolah')

@section('content')
    <div class="space-y-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Kurikulum</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($kurikulums) }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Status</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            @if ($kurikulumAktif)
                                {{ $kurikulumAktif->Status }}
                            @else
                                Belum ada kurikulum
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Kurikulum Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $kurikulumAktif->nama_kurikulum ?? '-' }}</p>
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
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kurikulum</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola dan atur kurikulum sekolah</p>
                    </div>
                    <a href="{{ route('Operator.Kurikulum.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Kurikulum
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if (count($kurikulums) > 0)
                    <div class="space-y-4">
                        @foreach ($kurikulums as $kurikulum)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <a href="{{ route('Operator.TahunAjaran.index', ['id_kurikulum' => $kurikulum->id_kurikulum]) }}"
                                            class="block text-xl font-semibold text-blue-600 hover:text-blue-800 hover:underline cursor-pointer transition-colors">
                                            {{ $kurikulum->nama_kurikulum }}
                                        </a>

                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                Dibuat:
                                                {{ $kurikulum->created_at ? $kurikulum->created_at->format('d M Y') : 'N/A' }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-edit mr-2"></i>
                                                Diperbarui:
                                                {{ $kurikulum->updated_at ? $kurikulum->updated_at->format('d M Y') : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Aktif
                                        </span>
                                        <a href="{{ route('Operator.Kurikulum.edit', $kurikulum->id_kurikulum) }}"
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
                            <i class="fas fa-book text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kurikulum</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan kurikulum pertama Anda.</p>
                        <a href="{{ route('Operator.Kurikulum.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Kurikulum
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
