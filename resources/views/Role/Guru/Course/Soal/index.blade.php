@extends('layouts.guru-layout')

@section('title', 'Guru | Ujian | Soal')

@section('content')
<div class="space-y-6">

    <!-- Stats Cards Section -->
<div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div class="flex-shrink-0">
            <i class="fas fa-book text-blue-600 text-2xl"></i>
        </div>
        <div class="ml-3">
            <p class="text-xs font-medium text-gray-600">Total Soal</p>
            <p class="text-lg font-semibold text-gray-900">{{ count($soals) }}</p>
        </div>
    </div>
</div>

    <!-- Soal Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        
        <!-- Button to Add Soal -->
        <div class="flex justify-end mb-4">
            <button onclick="showTipeSoalModal()" class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambahkan
            </button>
        </div>

        <!-- Modal: Pilih Tipe Soal -->
        <div id="tipeSoalModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-semibold text-gray-700 text-center">Pilih Tipe Soal</h2>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div onclick="pilihSoal('pilgan')" class="cursor-pointer p-4 border border-gray-300 rounded-lg text-center hover:bg-gray-100 transition">
                        <i class="fas fa-question-circle text-blue-500 text-3xl"></i>
                        <p class="mt-2 font-semibold">Pilihan Berganda</p>
                    </div>
                    <div onclick="pilihSoal('truefalse')" class="cursor-pointer p-4 border border-gray-300 rounded-lg text-center hover:bg-gray-100 transition">
                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                        <p class="mt-2 font-semibold">Benar/Salah</p>
                    </div>
                </div>
                <button onclick="closeTipeSoalModal()" class="mt-4 w-full bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600">Batal</button>
            </div>
        </div>

        <!-- Display Questions -->
        <div class="space-y-4">
            @if ($idUjian)
                @foreach ($soals as $soal)
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div class="mb-4 md:mb-0 md:flex-1">
                            <h3 class="text-lg font-semibold mb-2 break-words">{{ $soal->soal }}</h3>
                            <p class="text-sm text-gray-600">Jenis: {{ $soal->tipe_soal->nama_tipe_soal }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-5 justify-end flex-wrap">
                            <form action="{{ route('Guru.Soal.preview', $soal->id_soal) }}" method="GET" class="inline">
                                <button type="submit" class="text-yellow-500 flex items-center hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </button>
                            </form>

                            <form action="{{ route('Guru.Soal.destroy', $soal->id_soal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 flex items-center hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 rounded">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                            </form>

                            <form action="{{ route('Guru.Soal.edit', $soal->id_soal) }}" method="GET" class="inline">
                                <button type="submit" class="text-blue-500 flex items-center hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- JavaScript to Show Modal on Success -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @if (session('success'))
            var myModal = new bootstrap.Modal(document.getElementById('successModal'));
            myModal.show();
            setTimeout(function() {
                myModal.hide();
            }, 3000); // 3000ms = 3 detik
        @endif

        // Function to show the modal for selecting question type
        function showTipeSoalModal() {
            document.getElementById('tipeSoalModal').classList.remove('hidden');
        }

        // Function to close the modal
        function closeTipeSoalModal() {
            document.getElementById('tipeSoalModal').classList.add('hidden');
        }

        // Function to handle the selection of the question type
        function pilihSoal(tipe) {
            Swal.fire({
                title: 'Anda memilih ' + (tipe === 'pilgan' ? 'Pilgan' : tipe === 'truefalse' ? 'True/False' : 'Essay'),
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                closeTipeSoalModal();
                let url = `/Guru/Soal/create?type=${tipe}`;
                const idUjian = '{{ $idUjian ?? '' }}';
                const idLatihan = '{{ $idLatihan ?? '' }}';

                // Add id_ujian or id_latihan to URL if available
                if (idUjian) url += `&id_ujian=${idUjian}`;
                if (idLatihan) url += `&id_latihan=${idLatihan}`;

                // Redirect to the corresponding URL
                window.location.href = url;
            });
        }
    </script>
</div>
@endsection
