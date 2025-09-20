@extends('layouts.operator-layout')

@section('title', 'Daftar Semester')
@section('page-title', 'Daftar Semester')
@section('page-description', 'Kelola informasi semester akademik')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 max-w-5xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Semester</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($semesters) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-play-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Semester Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $semesters->where('status', 'Aktif')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tahun Akademik</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $semesters->pluck('tahun_akademik')->unique()->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm"
                role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Semester</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola informasi semester akademik</p>
                    </div>
                    <a href="{{ route('Operator.Semester.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Semester
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-1/3">
                        <label for="tahun_akademik" class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan
                            Tahun Akademik</label>
                        <div class="relative">
                            <select id="tahun_akademik" name="tahun_akademik"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none bg-white">
                                <option value="">Semua Tahun Akademik</option>
                                @foreach ($semesters->pluck('tahun_akademik')->unique() as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3">
                        <label for="jenis_semester" class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan
                            Jenis</label>
                        <div class="relative">
                            <select id="jenis_semester" name="jenis_semester"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none bg-white">
                                <option value="">Semua Jenis</option>
                                <option value="Ganjil">Semester Ganjil</option>
                                <option value="Genap">Semester Genap</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Berdasarkan
                            Status</label>
                        <div class="relative">
                            <select id="status" name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none bg-white">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if (count($semesters) > 0)
                    <div class="space-y-4">
                        @foreach ($semesters as $semester)
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 hover:from-purple-50 hover:to-indigo-50 hover:border-purple-200 semester-item"
                                data-tahun="{{ $semester->tahun_akademik }}" data-jenis="{{ $semester->jenis_semester }}"
                                data-status="{{ $semester->status }}">
                                <div
                                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="w-16 h-16 bg-gradient-to-br {{ $semester->jenis_semester == 'Ganjil' ? 'from-blue-500 to-purple-600' : 'from-green-500 to-teal-600' }} rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                <i
                                                    class="fas {{ $semester->jenis_semester == 'Ganjil' ? 'fa-calendar-alt' : 'fa-calendar-check' }} text-white text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                                    Semester {{ $semester->jenis_semester }}
                                                    {{ $semester->tahun_akademik }}
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar mr-2 text-blue-600"></i>
                                                        Tahun Akademik: {{ $semester->tahun_akademik }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i
                                                            class="fas {{ $semester->jenis_semester == 'Ganjil' ? 'fa-moon' : 'fa-sun' }} mr-2 text-purple-600"></i>
                                                        Jenis: Semester {{ $semester->jenis_semester }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar-day mr-2 text-green-600"></i>
                                                        Periode:
                                                        {{ $semester->tanggal_mulai ? \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d M Y') : 'Belum ditentukan' }}
                                                        -
                                                        {{ $semester->tanggal_selesai ? \Carbon\Carbon::parse($semester->tanggal_selesai)->format('d M Y') : 'Belum ditentukan' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i
                                                            class="fas fa-circle mr-2 {{ strtolower($semester->status) == 'aktif' ? 'text-green-600' : 'text-red-600' }}"></i>
                                                        Status: {{ $semester->status }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if (strtolower($semester->status) != 'aktif')
                                            <button onclick="activateSemester({{ $semester->id_semester }})"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors group">
                                                <i class="fas fa-play mr-2 group-hover:scale-110 transition-transform"></i>
                                                Aktifkan
                                            </button>
                                        @endif
                                        <a href="{{ route('Operator.Semester.edit', $semester->id_semester) }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors group">
                                            <i class="fas fa-edit mr-2 group-hover:scale-110 transition-transform"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('Operator.Semester.destroy', $semester->id_semester) }}"
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
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada semester</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan semester pertama Anda.</p>
                        <a href="{{ route('Operator.Semester.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Semester
                        </a>
                    </div>
                @endif
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
                        <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus semester ini? Semua data terkait
                            juga akan dihapus.</p>
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

    <!-- Activate Confirmation Modal -->
    <div id="activateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-play-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Aktivasi</h3>
                        <p class="text-sm text-gray-600">Apakah Anda yakin ingin mengaktifkan semester ini? Semester yang
                            sedang aktif akan dinonaktifkan.</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeActivateModal()"
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button id="confirmActivateBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Aktifkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delete confirmation functions
        let deleteForm = null;
        let activateSemesterId = null;

        function confirmDelete(button) {
            deleteForm = button.closest('form');
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteForm = null;
        }

        function activateSemester(semesterId) {
            activateSemesterId = semesterId;
            document.getElementById('activateModal').classList.remove('hidden');
        }

        function closeActivateModal() {
            document.getElementById('activateModal').classList.add('hidden');
            activateSemesterId = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteForm) {
                deleteForm.submit();
            }
        });

        document.getElementById('confirmActivateBtn').addEventListener('click', function() {
            if (activateSemesterId) {
                // Create form to activate semester
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/operator/semester/${activateSemesterId}/activate`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Filter semesters based on selected filters
        document.addEventListener('DOMContentLoaded', function() {
            const tahunSelect = document.getElementById('tahun_akademik');
            const jenisSelect = document.getElementById('jenis_semester');
            const statusSelect = document.getElementById('status');
            const semesterItems = document.querySelectorAll('.semester-item');

            function filterSemesters() {
                const selectedTahun = tahunSelect.value;
                const selectedJenis = jenisSelect.value;
                const selectedStatus = statusSelect.value;

                let visibleCount = 0;

                semesterItems.forEach(item => {
                    const itemTahun = item.getAttribute('data-tahun');
                    const itemJenis = item.getAttribute('data-jenis');
                    const itemStatus = item.getAttribute('data-status');

                    const tahunMatch = selectedTahun === '' || itemTahun === selectedTahun;
                    const jenisMatch = selectedJenis === '' || itemJenis === selectedJenis;
                    const statusMatch = selectedStatus === '' || itemStatus === selectedStatus;

                    if (tahunMatch && jenisMatch && statusMatch) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Remove existing no-results message
                const existingNoResults = document.getElementById('no-results');
                if (existingNoResults) {
                    existingNoResults.remove();
                }

                // Show no results message if no semesters are visible
                if (visibleCount === 0) {
                    const contentArea = document.querySelector('.p-6');
                    const noResults = document.createElement('div');
                    noResults.id = 'no-results';
                    noResults.className = 'mt-6 text-center py-8 bg-gray-50 rounded-lg border border-gray-200';
                    noResults.innerHTML = `
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-md font-medium text-gray-700 mb-2">Tidak ada semester yang ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-3">Coba ubah filter pencarian</p>
                        <div class="text-xs text-gray-400 bg-white px-3 py-2 rounded-md inline-block">
                            <i class="fas fa-filter mr-1"></i>
                            Filter aktif: ${getActiveFilters()}
                        </div>
                    `;

                    const semesterContainer = contentArea.querySelector('.space-y-4');
                    if (semesterContainer) {
                        contentArea.insertBefore(noResults, semesterContainer.nextSibling);
                    } else {
                        contentArea.appendChild(noResults);
                    }
                }
            }

            // Helper function to show active filters
            function getActiveFilters() {
                const filters = [];
                if (tahunSelect.value) {
                    filters.push(`Tahun: ${tahunSelect.value}`);
                }
                if (jenisSelect.value) {
                    filters.push(`Jenis: ${jenisSelect.value}`);
                }
                if (statusSelect.value) {
                    filters.push(`Status: ${statusSelect.value}`);
                }
                return filters.length > 0 ? filters.join(', ') : 'Tidak ada filter aktif';
            }

            tahunSelect.addEventListener('change', filterSemesters);
            jenisSelect.addEventListener('change', filterSemesters);
            statusSelect.addEventListener('change', filterSemesters);

            // Add hover effects and animations
            semesterItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
@endsection
