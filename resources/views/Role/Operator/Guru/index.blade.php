@extends('layouts.operator-layout')

@section('title', 'Daftar Guru')
@section('page-title', 'Daftar Guru')
@section('page-description', 'Kelola informasi guru dan akun pengajar')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Guru</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($gurus) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Guru Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $gurus->where('status', 'Aktif')->count() }}
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
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Guru</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola informasi guru dan akun pengajar</p>
                    </div>
                    <a href="{{ route('Operator.Guru.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Guru
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if (count($gurus) > 0)
                    <div class="space-y-4">
                        @foreach ($gurus as $teacher)
                            <div
                                class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300 hover:from-green-50 hover:to-emerald-50 hover:border-green-200">
                                <div
                                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                <i class="fas fa-user-tie text-white text-2xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                                    {{ $teacher->nama_guru }}
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-id-card mr-2 text-blue-600"></i>
                                                        NIP: {{ $teacher->nip }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-envelope mr-2 text-green-600"></i>
                                                        Email: {{ $teacher->user->email }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i
                                                            class="fas fa-circle mr-2 {{ strtolower($teacher->status) == 'aktif' ? 'text-green-600' : 'text-red-600' }}"></i>
                                                        Status: {{ $teacher->status }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('Operator.Guru.edit', $teacher->id_guru) }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors group">
                                            <i class="fas fa-edit mr-2 group-hover:scale-110 transition-transform"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('Operator.Guru.destroy', $teacher->id_guru) }}"
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
                            <i class="fas fa-chalkboard-teacher text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada guru</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan guru pertama Anda.</p>
                        <a href="{{ route('Operator.Guru.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Guru
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
                        <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus guru ini? Semua data terkait
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

    <script>
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
            const teacherItems = document.querySelectorAll('.teacher-item');

            teacherItems.forEach(item => {
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
