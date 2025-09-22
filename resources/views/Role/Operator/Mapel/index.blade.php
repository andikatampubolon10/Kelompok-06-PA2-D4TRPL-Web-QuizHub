@extends('layouts.operator-layout')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')
@section('page-description', 'Kelola informasi mata pelajaran sekolah')

@section('content')
    <div class="space-y-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Mata Pelajaran</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($mataPelajarans) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Kurikulum Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($kurikulums) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-layer-group text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Kategori</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $mataPelajarans->groupBy('id_kurikulum')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Status</p>
                        <p class="text-2xl font-semibold text-gray-900">Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Add Button Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex-1 max-w-md">
                    <label for="kurikulum" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-filter text-green-600 mr-2"></i>
                        Filter Berdasarkan Kurikulum
                    </label>
                    <select id="kurikulum" name="kurikulum"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        <option value="">Semua Kurikulum</option>
                        @foreach ($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id_kurikulum }}">{{ $kurikulum->nama_kurikulum }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-600">
                        <span id="showing-count">{{ count($mataPelajarans) }}</span> mata pelajaran ditampilkan
                    </div>
                    <a href="{{ route('Operator.MataPelajaran.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Mata Pelajaran
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Mata Pelajaran</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola dan atur mata pelajaran sekolah</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle"></i>
                        <span>Klik mata pelajaran untuk melihat detail</span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if (count($mataPelajarans) > 0)
                    <div id="mapel-list" class="space-y-4">
                        @foreach ($mataPelajarans as $mapel)
                            <div class="mapel-item bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 hover:from-green-50 hover:to-emerald-50 hover:border-green-200"
                                data-kurikulum="{{ $mapel->id_kurikulum }}">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-teal-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-book-open text-white text-lg"></i>
                                            </div>
                                            <div class="flex-1">
                                                <a href="{{ route('Operator.Course.beranda') }}">
                                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                                        {{ $mapel->nama_mata_pelajaran }}
                                                    </h4>
                                                </a>
                                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-layer-group mr-2 text-green-600"></i>
                                                        Kurikulum:
                                                        @foreach ($kurikulums as $kurikulum)
                                                            @if ($kurikulum->id_kurikulum == $mapel->id_kurikulum)
                                                                {{ $kurikulum->nama_kurikulum }}
                                                            @endif
                                                        @endforeach
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                                                        Dibuat:
                                                        {{ $mapel->created_at ? $mapel->created_at->format('d M Y') : 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="mt-2">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Aktif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('Operator.MataPelajaran.edit', $mapel->id_mata_pelajaran) }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors group">
                                            <i class="fas fa-edit mr-2 group-hover:scale-110 transition-transform"></i>
                                            Edit
                                        </a>
                                        <button
                                            onclick="showDetailModal('{{ $mapel->nama_mata_pelajaran }}', '{{ $kurikulums->where('id_kurikulum', $mapel->id_kurikulum)->first()->nama_kurikulum ?? 'N/A' }}', '{{ $mapel->created_at ? $mapel->created_at->format('d M Y, H:i') : 'N/A' }}')"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message (Hidden by default) -->
                    <div id="no-results" class="text-center py-12 hidden">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada mata pelajaran ditemukan</h3>
                        <p class="text-gray-600 mb-6">Coba ubah filter kurikulum atau tambah mata pelajaran baru.</p>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada mata pelajaran</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan mata pelajaran pertama Anda.</p>
                        <a href="{{ route('Operator.MataPelajaran.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Mata Pelajaran
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Mata Pelajaran</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nama Mata Pelajaran</p>
                                <p id="detailNama" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-layer-group text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Kurikulum</p>
                                <p id="detailKurikulum" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tanggal Dibuat</p>
                                <p id="detailTanggal" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeDetailModal()"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                </div>
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
                        <button onclick="closeSuccessModal()"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function closeSuccessModal() {
                document.getElementById('successModal').style.display = 'none';
            }

            // Auto close modal after 3 seconds
            setTimeout(closeSuccessModal, 3000);
        </script>
    @endif

    <script>
        // Filter functionality
        document.getElementById('kurikulum').addEventListener('change', function() {
            const selectedKurikulum = this.value;
            const mapelItems = document.querySelectorAll('.mapel-item');
            const noResults = document.getElementById('no-results');
            const showingCount = document.getElementById('showing-count');
            let visibleCount = 0;

            mapelItems.forEach(item => {
                const itemKurikulum = item.getAttribute('data-kurikulum');
                if (selectedKurikulum === '' || itemKurikulum === selectedKurikulum) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update showing count
            showingCount.textContent = visibleCount;

            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        });

        // Detail modal functions
        function showDetailModal(nama, kurikulum, tanggal) {
            document.getElementById('detailNama').textContent = nama;
            document.getElementById('detailKurikulum').textContent = kurikulum;
            document.getElementById('detailTanggal').textContent = tanggal;
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Add hover effects and animations
        document.addEventListener('DOMContentLoaded', function() {
            const mapelItems = document.querySelectorAll('.mapel-item');

            mapelItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection
