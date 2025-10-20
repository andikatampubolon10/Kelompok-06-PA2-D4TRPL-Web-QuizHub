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
        {{-- @dd($idUjian) --}}

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

            <!-- ============================================ -->
            <!-- Tabs Berdasarkan Jenis Soal -->
            <!-- ============================================ -->

            <ul class="nav nav-tabs mb-4" id="soalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pilgan-tab" data-bs-toggle="tab" data-bs-target="#pilgan"
                        type="button" role="tab">
                        Pilihan Ganda ({{ $countPilgan }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="truefalse-tab" data-bs-toggle="tab" data-bs-target="#truefalse"
                        type="button" role="tab">
                        True / False ({{ $countTrueFalse }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="essay-tab" data-bs-toggle="tab" data-bs-target="#essay" type="button"
                        role="tab">
                        Essay / Isian ({{ $countEssay }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="soalTabsContent">
                <!-- Tab: Pilihan Ganda -->
                <div class="tab-pane fade show active" id="pilgan" role="tabpanel">
                    @php
                        $pilgan = $soals->where('id_tipe_soal', 1);
                    @endphp
                    @forelse($pilgan as $soal)
                        <div
                            class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                            <div class="mb-4 md:mb-0 md:flex-1">
                                <h3 class="text-lg font-semibold mb-2 break-words">{!! $soal->soal !!}</h3>
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
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');"
                                    class="inline">
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
                        <div class="text-center py-6 text-gray-500">Belum ada soal pilihan ganda.</div>
                    @endforelse
                </div>

                <!-- Tab: True False -->
                <div class="tab-pane fade" id="truefalse" role="tabpanel">
                    @php
                        $truefalse = $soals->where('id_tipe_soal', 2);
                    @endphp
                    @forelse($truefalse as $soal)
                        <div
                            class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                            <div class="mb-4 md:mb-0 md:flex-1">
                                <h3 class="text-lg font-semibold mb-2 break-words">{!! $soal->soal !!}</h3>
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
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');"
                                    class="inline">
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
                        <div class="text-center py-6 text-gray-500">Belum ada soal true / false.</div>
                    @endforelse
                </div>

                <!-- Tab: Essay -->
                <div class="tab-pane fade" id="essay" role="tabpanel">
                    @php
                        $essay = $soals->where('id_tipe_soal', 3);
                    @endphp
                    @forelse($essay as $soal)
                        <div
                            class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                            <div class="mb-4 md:mb-0 md:flex-1">
                                <h3 class="text-lg font-semibold mb-2 break-words">{{ $soal->soal }}</h3>
                                <!-- Tampilkan soal dengan teks bersih -->
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
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');"
                                    class="inline">
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
                        <div class="text-center py-6 text-gray-500">Belum ada soal essay / isian.</div>
                    @endforelse
                </div>
            </div>

            <!-- Script -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                @if (session('success'))
                    var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                    myModal.show();
                @endif

                function showTipeSoalModal() {
                    document.getElementById('tipeSoalModal').classList.remove('hidden');
                }

                function closeTipeSoalModal() {
                    document.getElementById('tipeSoalModal').classList.add('hidden');
                }

                function pilihSoal(tipe) {
                    const idUjian = "{{ $idUjian }}"; // dari Blade
                    let url = '';

                    // Gunakan route yang benar sesuai parameter dinamis {type}
                    url = `{{ url('/Guru/Soal/create') }}/${tipe}?id_ujian=${idUjian}`;

                    window.location.href = url;
                }
            </script>

        </div>
    </div>
@endsection
