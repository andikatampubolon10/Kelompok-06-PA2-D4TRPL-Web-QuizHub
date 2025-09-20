@extends('layouts.admin-layout')

@section('page-title', 'Admin')
@section('page-description', 'Kelola Bisnis')

@section('content')
    <div class="space-y-6">
      <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Bisnis</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola dan atur Bisnis</p>
                    </div>
                    <a href="{{ route('Admin.Bisnis.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Bisnis
                    </a>
                </div>
            </div>


           <!-- Content -->
            <div class="p-6">
        @foreach ($bisnises as $bisnis)
            <section
                class="bg-white rounded-2xl shadow-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0 md:space-x-6 mb-6">
                <div class="flex flex-col space-y-2 max-w-xl">
                    <h3 class="text-2xl font-bold text-teal-700">
                        {{ $bisnis['nama_sekolah'] }}
                    </h3>
                    <p class="text-gray-700 text-base">
                        Jumlah Pendapatan: 
                        <span class="font-semibold text-black">
                            {{ $bisnis->jumlah_pendapatan }}
                        </span>
                    </p>
                </div>
                <div class="flex space-x-4">
                    <!-- Delete button -->
                    <form action="{{ route('Admin.Bisnis.destroy', $bisnis->id_bisnis) }}" method="POST" class="form-hapus">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center space-x-2 text-red-600 hover:text-red-500 font-semibold transition-transform duration-300 transform hover:scale-110">
                            <i class="fas fa-trash-alt text-lg"></i>
                            <span>Hapus</span>
                        </button>
                    </form>

                    <!-- Edit button -->
                    <form action="{{ route('Admin.Bisnis.edit', $bisnis->id_bisnis) }}" method="GET">
                        <button type="submit"
                            class="text-blue-600 flex items-center hover:text-blue-500 font-semibold transition-transform duration-300 transform hover:scale-110">
                            <i class="fas fa-edit text-lg"></i>
                            <span>Edit</span>
                        </button>
                    </form>
                </div>
            </section>
           @endforeach
          
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.form-hapus').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop submit default

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data bisnis akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Lanjutkan submit jika dikonfirmasi
                }
            });
        });
    });
        
    </script>
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

@endsection


