@extends('layouts.operator-layout')

@section('title', 'Daftar Kursus')
@section('page-title', 'Daftar Kursus')
@section('page-description', 'Kelola informasi guru dan kursus yang diampu')

@section('content')
    <div class="space-y-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Guru</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ count($guru ?? []) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book-open text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Kursus</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($courses) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Guru Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ count($courses ?? []) > 0 && count($gurus ?? []) > 0 ? round(count($courses) / count($gurus), 1) : 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Add Button Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col space-y-4">
                <!-- Filter Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="guru-filter" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-chalkboard-teacher text-blue-600 mr-2"></i>
                            Filter Berdasarkan Guru
                        </label>
                        <select id="guru-filter" name="guru-filter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Semua Guru</option>
                            @foreach ($guru as $gurus)
                                <option value="{{ $gurus->id_guru }}">{{ $gurus->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="kelas-filter" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-home text-purple-600 mr-2"></i>
                            Filter Berdasarkan Kelas
                        </label>
                        <select id="kelas-filter" name="kelas-filter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id_kelas }}">{{ $kelasItem->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Row -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            <span id="showing-count">{{ count($courses) }}</span> kursus ditampilkan
                        </div>
                        <button onclick="clearAllFilters()"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Reset Filter
                        </button>
                    </div>

                    <a href="{{ route('Operator.Course.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Kursus
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
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kursus</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola kursus yang diampu oleh guru</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle"></i>
                        <span>Klik kursus untuk melihat detail</span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if (count($courses) > 0)
                    <div id="course-list" class="space-y-4">
                        @foreach ($courses as $course)
                            <div class="course-item bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 hover:from-green-50 hover:to-emerald-50 hover:border-green-200"
                                data-guru="{{ $course->id_guru }}" data-kelas="{{ $course->id_kelas ?? '' }}">
                                <div
                                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="w-20 h-20 bg-gradient-to-br from-teal-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                @if ($course->image_url)
                                                    <img src="{{ $course->image_url }}" alt="{{ $course->nama_kursus }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-book-open text-white text-2xl"></i>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                                    {{ $course->nama_kursus }}
                                                </h4>
                                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-chalkboard-teacher mr-2 text-blue-600"></i>
                                                        Guru: {{ $course->guru->nama_guru }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-users mr-2 text-purple-600"></i>
                                                        Kelas: {{ $course->kelas->nama_kelas }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-book mr-2 text-green-600"></i>
                                                        Mata Pelajaran:
                                                        {{ $course->mataPelajaran->nama_mata_pelajaran ?? 'Tidak ada' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar-alt mr-2 text-orange-600"></i>
                                                        Dibuat:
                                                        {{ $course->created_at ? $course->created_at->format('d M Y') : 'N/A' }}
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
                                        <a href="{{ route('Operator.Course.edit', $course->id_kursus) }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors group">
                                            <i class="fas fa-edit mr-2 group-hover:scale-110 transition-transform"></i>
                                            Edit
                                        </a>
                                        <button
                                            onclick="showDetailModal('{{ $course->nama_kursus }}', '{{ $course->guru->nama_guru }}', '{{ $course->kelas->nama_kelas }}', '{{ $course->mataPelajaran->nama_mata_pelajaran ?? 'Tidak ada' }}', '{{ $course->created_at ? $course->created_at->format('d M Y, H:i') : 'N/A' }}')"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            Detail
                                        </button>
                                        <form action="{{ route('Operator.Course.destroy', $course->id_kursus) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                                <i class="fas fa-trash mr-2"></i>
                                                Hapus
                                            </button>
                                        </form>
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
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada kursus ditemukan</h3>
                        <p class="text-gray-600 mb-6">Coba ubah filter guru atau tambah kursus baru.</p>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book-open text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kursus</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan kursus pertama Anda.</p>
                        <a href="{{ route('Operator.Course.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Kursus
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
                    <h3 class="text-lg font-semibold text-gray-900">Detail Kursus</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-open text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nama Kursus</p>
                                <p id="detailNama" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Guru Pengampu</p>
                                <p id="detailGuru" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Kelas</p>
                                <p id="detailKelas" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                                <p id="detailMapel" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-orange-600"></i>
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus kursus ini?</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button id="confirmDeleteBtn"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
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
        // Enhanced Filter functionality
        function applyFilters() {
            const selectedGuru = document.getElementById('guru-filter').value;
            const selectedKelas = document.getElementById('kelas-filter').value;
            const courseItems = document.querySelectorAll('.course-item');
            const noResults = document.getElementById('no-results');
            const showingCount = document.getElementById('showing-count');
            let visibleCount = 0;

            courseItems.forEach(item => {
                const itemGuru = item.getAttribute('data-guru');
                const itemKelas = item.getAttribute('data-kelas');

                const guruMatch = selectedGuru === '' || itemGuru === selectedGuru;
                const kelasMatch = selectedKelas === '' || itemKelas === selectedKelas;

                if (guruMatch && kelasMatch) {
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
                // Update no results message based on active filters
                const noResultsText = document.querySelector('#no-results p');
                if (selectedGuru && selectedKelas) {
                    noResultsText.textContent = 'Tidak ada kursus yang sesuai dengan guru dan kelas yang dipilih.';
                } else if (selectedGuru) {
                    noResultsText.textContent = 'Tidak ada kursus untuk guru yang dipilih.';
                } else if (selectedKelas) {
                    noResultsText.textContent = 'Tidak ada kursus untuk kelas yang dipilih.';
                } else {
                    noResultsText.textContent = 'Coba ubah filter atau tambah kursus baru.';
                }
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Clear all filters
        function clearAllFilters() {
            document.getElementById('guru-filter').value = '';
            document.getElementById('kelas-filter').value = '';
            applyFilters();
        }

        // Add event listeners for both filters
        document.getElementById('guru-filter').addEventListener('change', applyFilters);
        document.getElementById('kelas-filter').addEventListener('change', applyFilters);

        // Detail modal functions
        function showDetailModal(nama, guru, kelas, mapel, tanggal) {
            document.getElementById('detailNama').textContent = nama;
            document.getElementById('detailGuru').textContent = guru;
            document.getElementById('detailKelas').textContent = kelas;
            document.getElementById('detailMapel').textContent = mapel;
            document.getElementById('detailTanggal').textContent = tanggal;
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Delete confirmation functions
        let deleteForm = null;

        function confirmDelete(button) {
            deleteForm = button.closest('form');
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteForm = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteForm) {
                deleteForm.submit();
            }
        });

        // Add hover effects and animations
        document.addEventListener('DOMContentLoaded', function() {
            const courseItems = document.querySelectorAll('.course-item');

            courseItems.forEach(item => {
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
