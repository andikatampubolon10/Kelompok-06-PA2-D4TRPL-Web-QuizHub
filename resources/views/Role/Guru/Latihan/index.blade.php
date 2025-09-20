@extends('layouts.guru-layout')

@section('title', 'Informasi Kurikulum')
@section('page-title', 'Kurikulum')
@section('page-description', 'Kelola informasi kurikulum sekolah')

@section('content')
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
            <div class="flex justify-end mb-6">
                <a href="{{ route('Guru.Latihan.create') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg flex items-center hover:bg-green-600 transition-all">
                    <i class="fas fa-plus mr-2"></i> Tambahkan
                </a>
            </div>

            

                <div id="latihanContainer" class="p-6 rounded-lg flex flex-col gap-4 mt-6">
                    @foreach ($latihan as $item)
                        <div class="mapel-item p-4 rounded-lg cursor-pointer hover:shadow-xl transition-shadow mb-4 flex justify-between items-center bg-gray-100 hover:bg-gray-200"
                             data-kurikulum="{{ $item->id_kurikulum }}"
                             data-mata_pelajaran="{{ $item->id_mata_pelajaran }}" data-kelas="{{ $item->id_kelas }}">

                            <a href="{{ route('Guru.Soal.index', ['id_latihan' => $item->id_latihan]) }}"
                               class="text-xl font-semibold text-teal-700 hover:underline focus:outline-none focus:ring-2 focus:ring-teal-400 w-3/4">
                                {{ $item->Topik }}
                            </a>

                            <!-- Action buttons: Delete and Edit in one row -->
                            <div class="flex space-x-4 justify-end">
                                <form action="{{ route('Guru.Latihan.destroy', $item->id_latihan) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus topik ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 flex items-center hover:text-red-700 transition-all">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>

                                <form action="{{ route('Guru.Latihan.edit', $item->id_latihan) }}" method="GET">
                                    <button type="submit" class="text-blue-500 flex items-center hover:text-blue-700 transition-all">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <!-- Success Modal -->
    @if (session('success'))
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
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

        <script>
            function closeModal() {
                document.getElementById('successModal').style.display = 'none';
            }

            // Auto close modal after 3 seconds
            setTimeout(closeModal, 3000);

            // Auto-submit form when filter values change
            document.querySelectorAll('#id_kurikulum, #id_mata_pelajaran, #kelas').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            });
        </script>
    @endif
@endsection
