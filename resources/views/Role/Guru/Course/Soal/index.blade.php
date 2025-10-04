@extends('layouts.guru-layout')

@section('title', 'Guru | Ujian | Soal')

@section('content')
    @php
        // Hitung total bobot per tipe
        $collection = $soals ?? collect();
        $sumPilgan = (float) $collection->where('id_tipe_soal', 1)->sum('bobot');
        $sumTrueFalse = (float) $collection->where('id_tipe_soal', 2)->sum('bobot');
        $sumEssay = (float) $collection->where('id_tipe_soal', 3)->sum('bobot');

        // Panel warna: hijau jika minimal salah satu tipe sudah 100/100, selain itu kuning
        $any100 = $sumPilgan == 100 || $sumTrueFalse == 100 || $sumEssay == 100;
        $boxBG = $any100 ? 'bg-green-50' : 'bg-yellow-50';
        $boxBorder = $any100 ? 'border-green-200' : 'border-yellow-200';
        $iconCol = $any100 ? 'text-green-600' : 'text-yellow-600';
        $titleCol = $any100 ? 'text-green-800' : 'text-yellow-800';
        $bodyCol = $any100 ? 'text-green-700' : 'text-yellow-700';
    @endphp
    @php
        $collection = $soals ?? collect();

        $countPilgan = $collection->where('id_tipe_soal', 1)->count();
        $countTrueFalse = $collection->where('id_tipe_soal', 2)->count();
        $countEssay = $collection->where('id_tipe_soal', 3)->count();
        $countTotal = $collection->count();
    @endphp

    <!-- Stats Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Pilihan Berganda -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="fas fa-list-ul text-blue-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-600">Pilihan Berganda</p>
                <p class="text-xl font-bold text-gray-900">{{ $countPilgan }}</p>
            </div>
        </div>

        <!-- True / False -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="fas fa-check-double text-green-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-600">True / False</p>
                <p class="text-xl font-bold text-gray-900">{{ $countTrueFalse }}</p>
            </div>
        </div>

        <!-- Isian / Essay -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                <i class="fas fa-pen-nib text-indigo-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-600">Isian / Essay</p>
                <p class="text-xl font-bold text-gray-900">{{ $countEssay }}</p>
            </div>
        </div>

        <!-- Total Semua Soal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center">
                <i class="fas fa-book text-yellow-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-600">Total Soal</p>
                <p class="text-xl font-bold text-gray-900">{{ $countTotal }}</p>
            </div>
        </div>

    </div>

    <!-- Additional Info Section (Ringkasan Bobot per Tipe) -->
    <div class="{{ $boxBG }} border {{ $boxBorder }} rounded-lg p-4 mb-8">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-white/60 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb {{ $iconCol }} text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold {{ $titleCol }} mb-1">Jumlah Bobot Soal</h4>
                <ul class="text-xs {{ $bodyCol }} space-y-1">
                    <li>
                        Pilihan Berganda :
                        <span class="font-semibold">
                            {{ rtrim(rtrim(number_format($sumPilgan, 2, '.', ''), '0'), '.') }}/100
                        </span>
                    </li>
                    <li>
                        True / False :
                        <span class="font-semibold">
                            {{ rtrim(rtrim(number_format($sumTrueFalse, 2, '.', ''), '0'), '.') }}/100
                        </span>
                    </li>
                    <li>
                        Isian :
                        <span class="font-semibold">
                            {{ rtrim(rtrim(number_format($sumEssay, 2, '.', ''), '0'), '.') }}/100
                        </span>
                    </li>
                </ul>
                @unless ($any100)
                    <p class="text-[11px] mt-2 {{ $bodyCol }}">
                        Naikkan bobot hingga <strong>minimal salah satu</strong> tipe mencapai <strong>100/100</strong> agar
                        panel ini berwarna hijau.
                    </p>
                @endunless
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Soal Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">

            <!-- Aksi: Tambah Soal -->
            <div class="flex items-center justify-end mb-4 gap-3">
                <button onclick="showTipeSoalModal()"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambahkan Soal
                </button>
            </div>

            <!-- Modal: Pilih Tipe Soal -->
            <div id="tipeSoalModal"
                class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mx-4">
                    <h2 class="text-lg font-semibold text-gray-700 text-center">Pilih Tipe Soal</h2>
                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <div onclick="pilihSoal('pilgan')"
                            class="cursor-pointer p-4 border border-gray-300 rounded-lg text-center hover:bg-gray-100 transition">
                            <i class="fas fa-question-circle text-blue-500 text-3xl"></i>
                            <p class="mt-2 font-semibold">Pilihan Berganda</p>
                        </div>
                        <div onclick="pilihSoal('truefalse')"
                            class="cursor-pointer p-4 border border-gray-300 rounded-lg text-center hover:bg-gray-100 transition">
                            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                            <p class="mt-2 font-semibold">Benar/Salah</p>
                        </div>
                        <div onclick="pilihSoal('essay')"
                            class="cursor-pointer p-4 border border-gray-300 rounded-lg text-center hover:bg-gray-100 transition">
                            <i class="fas fa-pen-nib text-indigo-500 text-3xl"></i>
                            <p class="mt-2 font-semibold">Essay / Isian</p>
                        </div>
                    </div>
                    <button onclick="closeTipeSoalModal()"
                        class="mt-4 w-full bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600">Batal</button>
                </div>
            </div>

            <!-- Daftar Soal -->
            <div class="space-y-4">
                @forelse(($soals ?? collect()) as $soal)
                    <div
                        class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div class="mb-4 md:mb-0 md:flex-1">
                            <h3 class="text-lg font-semibold mb-2 break-words">{{ $soal->soal }}</h3>

                            <p class="text-sm text-gray-600">
                                Jenis: {{ optional($soal->tipe_soal)->nama_tipe_soal ?? '—' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Bobot: <span class="font-semibold">
                                    {{ is_null($soal->bobot) ? '—' : rtrim(rtrim(number_format((float) $soal->bobot, 2, '.', ''), '0'), '.') }}
                                </span>
                            </p>
                        </div>

                        <div class="flex space-x-5 justify-end flex-wrap">
                            <a href="{{ route('Guru.Soal.preview', $soal->id_soal) }}"
                                class="text-yellow-500 flex items-center hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>

                            <form action="{{ route('Guru.Soal.destroy', $soal->id_soal) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 flex items-center hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 rounded">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                            </form>

                            <a href="{{ route('Guru.Soal.edit', $soal->id_soal) }}"
                                class="text-blue-500 flex items-center hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-100">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada Soal</h3>
                        <p class="text-gray-600">Tambahkan soal baru untuk
                            {{ $idUjian ? 'ujian ini' : ($idLatihan ? 'latihan ini' : 'kelas ini') }}.</p>
                    </div>
                @endforelse
            </div>

            <!-- Script -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                @if (session('success'))
                    var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                    myModal.show();
                    setTimeout(function() {
                        myModal.hide();
                    }, 3000);
                @endif

                function showTipeSoalModal() {
                    document.getElementById('tipeSoalModal').classList.remove('hidden');
                }

                function closeTipeSoalModal() {
                    document.getElementById('tipeSoalModal').classList.add('hidden');
                }

                function pilihSoal(tipe) {
                    Swal.fire({
                        title: 'Anda memilih ' + (tipe === 'pilgan' ? 'Pilgan' : tipe === 'truefalse' ? 'True/False' :
                            'Essay / Isian'),
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        closeTipeSoalModal();
                        let url = `/Guru/Soal/create?type=${tipe}`;
                        const idUjian = '{{ $idUjian ?? '' }}';
                        const idLatihan = '{{ $idLatihan ?? '' }}';
                        if (idUjian) url += `&id_ujian=${idUjian}`;
                        if (idLatihan) url += `&id_latihan=${idLatihan}`;
                        window.location.href = url;
                    });
                }
            </script>
        </div>
    </div>
@endsection
