@extends('layouts.admin-layout')

@section('page-title', 'Admin')
@section('page-description', 'Kelola akun operator')

@section('content')
    <div class="space-y-6">

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Operator</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola dan atur Operator sekolah</p>
                    </div>
                    <a href="{{ route('Admin.Akun.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Operator
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if($operators->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada Operator</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan Operator pertama Anda.</p>
                        <a href="{{ route('Admin.Akun.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Operator
                        </a>
                    </div>
                @else
                    @foreach ($operators as $operator)
                        <section
                            class="bg-white rounded-2xl shadow-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0 md:space-x-6 mb-4">
                            <div class="flex flex-col space-y-2 max-w-xl">
                                <h2 class="text-2xl font-bold text-teal-700">
                                    {{ $operator['nama_sekolah'] }}
                                </h2>
                                <p class="text-gray-700 text-base">
                                    Email:
                                    <span class="font-semibold text-black">
                                        {{ $operator->user['email'] }}
                                    </span>
                                </p>
                                <p class="text-gray-700 text-base">
                                    Durasi:
                                    <span class="font-semibold text-black">
                                        {{ $operator['durasi'] }}
                                    </span>
                                </p>
                                <p class="text-gray-700 text-base">
                                    Status:
                                    <span class="font-semibold text-black">
                                        {{ $operator['status'] }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex flex-col md:flex-row space-x-0 md:space-x-6">
                                <form action="{{ route('Admin.Akun.destroy', $operator->id_operator) }}"
                                      method="POST" class="flex items-center delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center space-x-2 text-red-600 hover:text-red-500 font-semibold transition-transform duration-300 transform hover:scale-110">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                                <form action="{{ route('Admin.Akun.edit', $operator->user->id) }}" class="flex items-center"
                                    method="GET">
                                    <button class="text-blue-500 flex items-center hover:text-blue-400">
                                        <i class="fas fa-edit text-lg"></i>
                                        <span>Edit</span>
                                    </button>
                                </form>
                            </div>
                        </section>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    
@endsection
@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah form submit langsung

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit form secara manual jika user klik "Ya"
                        }
                    });
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif
@endsection

