{{-- Fixed: Changed grid-cols-4 to grid-cols-3 for stats cards (only 3 cards). --}}
{{-- Fixed: Corrected "Guru Aktif" calculation to count unique gurus with courses instead of average. --}}
{{-- Fixed: Ensured safe handling of collections/arrays with ?? []. --}}
{{-- Fixed: Added @forelse for gurus and kelas loops to handle empty/null cases gracefully. --}}
{{-- Fixed: Minor optimizations for consistency, accessibility, and error prevention (e.g., relationship null checks). --}}
{{-- Fixed: Ensured all relationships (guru, kelas, mataPelajaran) have safe fallbacks. --}}
{{-- Fixed: Improved JS for better error handling and accessibility (e.g., ARIA labels). --}}
{{-- Overall: Code is now more robust, performant, and follows Laravel Blade best practices. No variable collisions remain. --}}

@extends('layouts.operator-layout')

@section('title', 'Daftar Kursus')
@section('page-title', 'Daftar Kursus')
@section('page-description', 'Kelola informasi guru dan kursus yang diampu')

@section('content')
    <div class="space-y-6">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-blue-600 text-xl" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Guru</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ count($gurus ?? []) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book-open text-green-600 text-xl" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Kursus</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($courses ?? []) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-xl" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Guru Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ collect($courses ?? [])->pluck('id_guru')->unique()->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter and Add Button Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col space-y-4">
                {{-- Filter Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="guru-filter" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-chalkboard-teacher text-blue-600 mr-2" aria-hidden="true"></i>
                            Filter Berdasarkan Guru
                        </label>
                        <select id="guru-filter" name="guru-filter" aria-label="Filter berdasarkan guru"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Semua Guru</option>
                            @forelse ($gurus ?? [] as $g)
                                <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                            @empty
                                <option value="" disabled>Tidak ada guru tersedia</option>
                            @endforelse
                        </select>
                    </div>

                    <div>
                        <label for="kelas-filter" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-home text-purple-600 mr-2" aria-hidden="true"></i>
                            Filter Berdasarkan Kelas
                        </label>
                        <select id="kelas-filter" name="kelas-filter" aria-label="Filter berdasarkan kelas"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <option value="">Semua Kelas</option>
                            @forelse ($kelas ?? [] as $kelasItem)
                                <option value="{{ $kelasItem->id_kelas }}">{{ $kelasItem->nama_kelas }}</option>
                            @empty
                                <option value="" disabled>Tidak ada kelas tersedia</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            <span id="showing-count">{{ count($courses ?? []) }}</span> kursus ditampilkan
                        </div>
                        <button onclick="clearAllFilters()" type="button"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            aria-label="Reset filter">
                            <i class="fas fa-times mr-2" aria-hidden="true"></i>
                            Reset Filter
                        </button>
                    </div>

                        <a href="{{ route('Operator.Course.create', ['id_mata_pelajaran' => $id_mata_pelajaran]) }}"
                            aria-label="Tambah kursus pertama"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2" aria-hidden="true"></i>
                            Tambah Kursus
                        </a>
                </div>
            </div>
        </div>

        {{-- Main Content Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kursus</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola kursus yang diampu oleh guru</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                        <span>Klik kursus untuk melihat detail</span>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6">
                @if (count($courses ?? []) > 0)
                    <div id="course-list" class="space-y-4">
                        @foreach ($courses as $course)
                            <div class="course-item bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 hover:from-green-50 hover:to-emerald-50 hover:border-green-200"
                                data-guru="{{ $course->id_guru ?? '' }}" data-kelas="{{ $course->id_kelas ?? '' }}"
                                role="article" tabindex="0">
                                <div
                                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="w-20 h-20 bg-gradient-to-br from-teal-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                @if ($course->image_url)
                                                    <img src="{{ $course->image_url }}"
                                                        alt="Gambar kursus {{ $course->nama_kursus }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-book-open text-white text-2xl" aria-hidden="true"></i>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                                    {{ $course->nama_kursus }}
                                                </h4>
                                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-chalkboard-teacher mr-2 text-blue-600"
                                                            aria-hidden="true"></i>
                                                        Guru: {{ $course->guru->nama_guru ?? 'Tidak ditentukan' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-users mr-2 text-purple-600"
                                                            aria-hidden="true"></i>
                                                        Kelas: {{ $course->kelas->nama_kelas ?? 'Tidak ditentukan' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-book mr-2 text-green-600" aria-hidden="true"></i>
                                                        Mata Pelajaran:
                                                        {{ $course->mataPelajaran->nama_mata_pelajaran ?? 'Tidak ada' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar-alt mr-2 text-orange-600"
                                                            aria-hidden="true"></i>
                                                        Dibuat:
                                                        {{ $course->created_at ? $course->created_at->format('d M Y') : 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="mt-2">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>
                                                        Aktif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('Operator.Course.edit', $course->id_kursus) }}"
                                            aria-label="Edit kursus {{ $course->nama_kursus }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors group">
                                            <i class="fas fa-edit mr-2 group-hover:scale-110 transition-transform"
                                                aria-hidden="true"></i>
                                            Edit
                                        </a>
                                        <button type="button"
                                            onclick="showDetailModal('{{ addslashes($course->nama_kursus) }}', '{{ addslashes($course->guru->nama_guru ?? 'Tidak ditentukan') }}', '{{ addslashes($course->kelas->nama_kelas ?? 'Tidak ditentukan') }}', '{{ addslashes($course->mataPelajaran->nama_mata_pelajaran ?? 'Tidak ada') }}', '{{ $course->created_at ? $course->created_at->format('d M Y, H:i') : 'N/A' }}')"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                            aria-label="Lihat detail kursus {{ $course->nama_kursus }}">
                                            <i class="fas fa-eye mr-2" aria-hidden="true"></i>
                                            Detail
                                        </button>
                                        <form action="{{ route('Operator.Course.destroy', $course->id_kursus) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)"
                                                aria-label="Hapus kursus {{ $course->nama_kursus }}"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                                <i class="fas fa-trash mr-2" aria-hidden="true"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- No Results Message (Hidden by default) --}}
                    <div id="no-results" class="text-center py-12 hidden">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-3xl" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada kursus ditemukan</h3>
                        <p class="text-gray-600 mb-6" id="no-results-text">Coba ubah filter guru atau tambah kursus baru.
                        </p>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book-open text-gray-400 text-3xl" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kursus</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan kursus pertama Anda.</p>
                        @if ($courses->isEmpty())
                            <a href="{{ route('Operator.Course.create', ['id_mata_pelajaran' => $id_mata_pelajaran]) }}"
                                aria-label="Tambah kursus pertama"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-plus mr-2" aria-hidden="true"></i>
                                Tambah Kursus
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
        role="dialog" aria-modal="true" aria-labelledby="detailModalTitle">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="detailModalTitle" class="text-lg font-semibold text-gray-900">Detail Kursus</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600"
                        aria-label="Tutup modal detail">
                        <i class="fas fa-times text-xl" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-open text-green-600" aria-hidden="true"></i>
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
                                <i class="fas fa-chalkboard-teacher text-blue-600" aria-hidden="true"></i>
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
                                <i class="fas fa-users text-purple-600" aria-hidden="true"></i>
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
                                <i class="fas fa-book text-green-600" aria-hidden="true"></i>
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
                                <i class="fas fa-calendar text-orange-600" aria-hidden="true"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tanggal Dibuat</p>
                                <p id="detailTanggal" class="text-lg font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeDetailModal()" type="button"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        aria-label="Tutup modal detail">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
        role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h3 id="deleteModalTitle" class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus kursus ini? Tindakan ini tidak
                            dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()" type="button"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        aria-label="Batal hapus">
                        Batal
                    </button>
                    <button id="confirmDeleteBtn" type="button"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        aria-label="Konfirmasi hapus kursus">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    @if (session('success'))
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            role="alertdialog" aria-labelledby="successModalTitle">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check text-green-600 text-xl" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 id="successModalTitle" class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                            <p class="text-sm text-gray-600">{{ session('success') }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button onclick="closeSuccessModal()" type="button"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors"
                            aria-label="Tutup notifikasi sukses">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function closeSuccessModal() {
                const modal = document.getElementById('successModal');
                if (modal) {
                    modal.style.display = 'none';
                }
            }
            // Auto-close after 3 seconds
            setTimeout(closeSuccessModal, 3000);
        </script>
    @endif

    <script>
        // Enhanced Filter functionality with error handling
        function applyFilters() {
            try {
                const selectedGuru = document.getElementById('guru-filter')?.value || '';
                const selectedKelas = document.getElementById('kelas-filter')?.value || '';
                const courseItems = document.querySelectorAll('.course-item');
                const noResults = document.getElementById('no-results');
                const showingCount = document.getElementById('showing-count');
                const noResultsText = document.getElementById('no-results-text');
                let visibleCount = 0;

                if (!courseItems.length) return;

                courseItems.forEach(item => {
                    const itemGuru = item.getAttribute('data-guru') || '';
                    const itemKelas = item.getAttribute('data-kelas') || '';

                    const guruMatch = !selectedGuru || itemGuru === selectedGuru;
                    const kelasMatch = !selectedKelas || itemKelas === selectedKelas;

                    if (guruMatch && kelasMatch) {
                        item.style.display = 'block';
                        item.setAttribute('aria-hidden', 'false');
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                        item.setAttribute('aria-hidden', 'true');
                    }
                });

                if (showingCount) {
                    showingCount.textContent = visibleCount;
                }

                if (noResults && visibleCount === 0) {
                    noResults.classList.remove('hidden');
                    if (noResultsText) {
                        if (selectedGuru && selectedKelas) {
                            noResultsText.textContent = 'Tidak ada kursus yang sesuai dengan guru dan kelas yang dipilih.';
                        } else if (selectedGuru) {
                            noResultsText.textContent = 'Tidak ada kursus untuk guru yang dipilih.';
                        } else if (selectedKelas) {
                            noResultsText.textContent = 'Tidak ada kursus untuk kelas yang dipilih.';
                        } else {
                            noResultsText.textContent = 'Coba ubah filter atau tambah kursus baru.';
                        }
                    }
                } else if (noResults) {
                    noResults.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error applying filters:', error);
            }
        }

        function clearAllFilters() {
            const guruFilter = document.getElementById('guru-filter');
            const kelasFilter = document.getElementById('kelas-filter');
            if (guruFilter) guruFilter.value = '';
            if (kelasFilter) kelasFilter.value = '';
            applyFilters();
        }

        // Event listeners for filters
        document.addEventListener('DOMContentLoaded', function() {
            const guruFilter = document.getElementById('guru-filter');
            const kelasFilter = document.getElementById('kelas-filter');
            if (guruFilter) guruFilter.addEventListener('change', applyFilters);
            if (kelasFilter) kelasFilter.addEventListener('change', applyFilters);

            // Enhanced hover and focus effects for accessibility
            const courseItems = document.querySelectorAll('.course-item');
            courseItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
                item.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.outline = '2px solid #3b82f6';
                });
                item.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.outline = 'none';
                });
            });
        });

        // Detail modal functions with error handling
        function showDetailModal(nama, guru, kelas, mapel, tanggal) {
            try {
                const modal = document.getElementById('detailModal');
                const elements = {
                    detailNama: document.getElementById('detailNama'),
                    detailGuru: document.getElementById('detailGuru'),
                    detailKelas: document.getElementById('detailKelas'),
                    detailMapel: document.getElementById('detailMapel'),
                    detailTanggal: document.getElementById('detailTanggal')
                };

                if (modal && elements.detailNama) {
                    elements.detailNama.textContent = nama || '-';
                    elements.detailGuru.textContent = guru || '-';
                    elements.detailKelas.textContent = kelas || '-';
                    elements.detailMapel.textContent = mapel || '-';
                    elements.detailTanggal.textContent = tanggal || '-';
                    modal.classList.remove('hidden');
                    modal.focus(); // Accessibility: Focus on modal when opened
                }
            } catch (error) {
                console.error('Error showing detail modal:', error);
            }
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Delete confirmation with improved handling
        let deleteForm = null;

        function confirmDelete(button) {
            deleteForm = button.closest('form');
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.focus(); // Accessibility
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
            }
            deleteForm = null;
        }

        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                if (deleteForm) {
                    deleteForm.submit();
                }
            });
        }

        // Keyboard accessibility for modals (ESC to close)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetailModal();
                closeDeleteModal();
                closeSuccessModal();
            }
        });
    </script>
@endsection
