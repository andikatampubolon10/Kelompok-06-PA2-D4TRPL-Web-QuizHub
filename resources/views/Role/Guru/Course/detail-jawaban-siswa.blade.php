@extends('layouts.guru-layout')

@section('title', 'Jawaban ' . $siswa->nama_siswa . ' | ' . $ujian->nama_ujian)

@section('content')
@php
    // ===== Ringkasan bobot =====
    $any100   = ($sumPilgan == 100) || ($sumTrueFalse == 100) || ($sumEssay == 100);
    $boxBG    = $any100 ? 'bg-green-50' : 'bg-yellow-50';
    $boxBorder= $any100 ? 'border-green-200' : 'border-yellow-200';
    $iconCol  = $any100 ? 'text-green-600' : 'text-yellow-600';
    $titleCol = $any100 ? 'text-green-800' : 'text-yellow-800';
    $bodyCol  = $any100 ? 'text-green-700' : 'text-yellow-700';
@endphp

<div class="mb-4">
  <h2 class="text-2xl font-bold text-teal-700">Jawaban Siswa</h2>
  <p class="text-gray-600">
    Ujian: <span class="font-semibold">{{ $ujian->nama_ujian }}</span> —
    Siswa: <span class="font-semibold">{{ $siswa->nama_siswa }}</span>
  </p>
</div>

{{-- ====== Kartu statistik (copy gaya halaman soal) ====== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
      <i class="fas fa-list-ul text-blue-600 text-lg"></i>
    </div>
    <div>
      <p class="text-xs uppercase tracking-wide text-gray-600">Pilihan Berganda</p>
      <p class="text-xl font-bold text-gray-900">{{ $countPilgan }}</p>
    </div>
  </div>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
      <i class="fas fa-check-double text-green-600 text-lg"></i>
    </div>
    <div>
      <p class="text-xs uppercase tracking-wide text-gray-600">True / False</p>
      <p class="text-xl font-bold text-gray-900">{{ $countTrueFalse }}</p>
    </div>
  </div>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
      <i class="fas fa-pen-nib text-indigo-600 text-lg"></i>
    </div>
    <div>
      <p class="text-xs uppercase tracking-wide text-gray-600">Isian / Essay</p>
      <p class="text-xl font-bold text-gray-900">{{ $countEssay }}</p>
    </div>
  </div>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-3">
    <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center">
      <i class="fas fa-book text-yellow-600 text-lg"></i>
    </div>
    <div>
      <p class="text-xs uppercase tracking-wide text-gray-600">Total Soal Dijawab</p>
      <p class="text-xl font-bold text-gray-900">{{ $countTotal }}</p>
    </div>
  </div>
</div>

{{-- ===== Ringkasan bobot per tipe ===== --}}
<div class="{{ $boxBG }} border {{ $boxBorder }} rounded-lg p-4 mb-8">
  <div class="flex items-start space-x-3">
    <div class="w-8 h-8 bg-white/60 rounded-full flex items-center justify-center flex-shrink-0">
      <i class="fas fa-lightbulb {{ $iconCol }} text-sm"></i>
    </div>
    <div>
      <h4 class="text-sm font-semibold {{ $titleCol }} mb-1">Jumlah Bobot Soal (yang dijawab siswa)</h4>
      <ul class="text-xs {{ $bodyCol }} space-y-1">
        <li>Pilihan Berganda :
          <span class="font-semibold">{{ rtrim(rtrim(number_format($sumPilgan, 2, '.', ''), '0'), '.') }}/100</span>
        </li>
        <li>True / False :
          <span class="font-semibold">{{ rtrim(rtrim(number_format($sumTrueFalse, 2, '.', ''), '0'), '.') }}/100</span>
        </li>
        <li>Isian :
          <span class="font-semibold">{{ rtrim(rtrim(number_format($sumEssay, 2, '.', ''), '0'), '.') }}/100</span>
        </li>
      </ul>
    </div>
  </div>
</div>

{{-- ====== Tabs berdasar tipe soal ====== --}}
@php
  $pilgan    = $soals->where('id_tipe_soal', 1);
  $truefalse = $soals->where('id_tipe_soal', 2);
  $essay     = $soals->where('id_tipe_soal', 3);
@endphp

<ul class="nav nav-tabs mb-4" id="soalTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pilgan-tab" data-bs-toggle="tab" data-bs-target="#pilgan" type="button" role="tab">
      Pilihan Ganda ({{ $pilgan->count() }})
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="truefalse-tab" data-bs-toggle="tab" data-bs-target="#truefalse" type="button" role="tab">
      True / False ({{ $truefalse->count() }})
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="essay-tab" data-bs-toggle="tab" data-bs-target="#essay" type="button" role="tab">
      Essay / Isian ({{ $essay->count() }})
    </button>
  </li>
</ul>

<div class="tab-content" id="soalTabsContent">
{{-- ===== Tab Pilihan Ganda ===== --}}
<div class="tab-pane fade show active" id="pilgan" role="tabpanel">
  @php
    $pilgan = $soals->where('id_tipe_soal', 1);
  @endphp

  @forelse($pilgan as $soal)
    @php
      $j         = $jawabanBySoal[$soal->id_soal] ?? null;
      $idDipilih = $j->id_jawaban_soal ?? null;
    @endphp

    {{-- Kartu ringkas --}}
    <div class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
      <div class="mb-4 md:mb-0 md:flex-1">
        <h3 class="text-lg font-semibold mb-2 soal-snippet">{!! $soal->soal !!}</h3>

        <div class="flex space-x-5 justify-end">
          <button type="button"
                  class="text-yellow-500 flex items-center hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded"
                  data-bs-toggle="modal" data-bs-target="#modal-soal-{{ $soal->id_soal }}">
            <i class="fas fa-eye mr-1"></i> Lihat
          </button>
        </div>

        <p class="text-sm text-gray-700 mt-2">
          Jawaban siswa:
          <span class="font-semibold">
            {{ optional($j?->jawaban_soal)->jawaban ?? ($j->jawaban_siswa ?? '—') }}
          </span>
        </p>
      </div>
    </div>

    {{-- Modal detail --}}
    <div class="modal fade" id="modal-soal-{{ $soal->id_soal }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Soal & Jawaban Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3 text-sm text-gray-600">
              Tipe: <span class="font-semibold">{{ optional($soal->tipe_soal)->nama_tipe_soal ?? '—' }}</span> •
              Bobot: <span class="font-semibold">{{ is_null($soal->bobot) ? '—' : rtrim(rtrim(number_format((float)$soal->bobot, 2, '.', ''), '0'), '.') }}</span>
            </div>

            <div class="prose max-w-none mb-4">{!! $soal->soal !!}</div>

            <h6 class="font-semibold mb-2">Opsi Jawaban</h6>
            <ul class="space-y-2">
              @forelse($soal->jawaban_soal as $ops)
                @php
                  $teksOpsi = $ops->jawaban ?? '(kosong)';
                    $isBenar  = (int)($ops->benar ?? 0) === 1; // kolom "benar"
                  $dipilih  = $idDipilih && $idDipilih == $ops->id_jawaban_soal;
                @endphp
                <li class="p-3 rounded border {{ $isBenar ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-white' }}">
                  <div class="flex items-start justify-between">
                    <div class="mr-2">{{ $teksOpsi }}</div>
                    <div class="flex items-center gap-2">
                      @if($isBenar)
                        <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-700">Kunci</span>
                      @endif
                      @if($dipilih)
                        <span class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700">Dipilih Siswa</span>
                      @endif
                    </div>
                  </div>
                </li>
              @empty
                <li class="text-gray-500">Belum ada opsi jawaban.</li>
              @endforelse
            </ul>

            @php
              $opsBenar  = $soal->jawaban_soal->firstWhere('benar', 1);
              $teksBenar = $opsBenar ? ($opsBenar->jawaban ?? '(kosong)') : null;
            @endphp
            <div class="mt-4">
              <h6 class="font-semibold mb-2">Jawaban Benar</h6>
              <div class="p-3 rounded {{ $teksBenar ? 'border border-green-300 bg-green-50' : 'border border-gray-200 bg-gray-50' }}">
                {{ $teksBenar ?? 'Belum ditandai' }}
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="text-center py-6 text-gray-500">Tidak ada jawaban Pilihan Ganda.</div>
  @endforelse
</div>


  {{-- ===== Tab True/False ===== --}}
<div class="tab-pane fade" id="truefalse" role="tabpanel">
  @php
    $truefalse = $soals->where('id_tipe_soal', 2);
  @endphp

  @forelse($truefalse as $soal)
    @php
      $j          = $jawabanBySoal[$soal->id_soal] ?? null;
      $idDipilih  = $j->id_jawaban_soal ?? null;
      $opsBenarTF = $soal->jawaban_soal->firstWhere('benar', 1);
      $teksBenarTF= $opsBenarTF ? ($opsBenarTF->jawaban ?? '(kosong)') : null;
    @endphp

    {{-- Kartu ringkas --}}
    <div class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
      <div class="mb-4 md:mb-0 md:flex-1">
        <h3 class="text-lg font-semibold mb-2 soal-snippet">{!! $soal->soal !!}</h3>

        <div class="flex space-x-5 justify-end">
          <button type="button"
                  class="text-yellow-500 flex items-center hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded"
                  data-bs-toggle="modal" data-bs-target="#modal-soal-tf-{{ $soal->id_soal }}">
            <i class="fas fa-eye mr-1"></i> Lihat
          </button>
        </div>

        <p class="text-sm text-gray-700 mt-2">
          Jawaban siswa:
          <span class="font-semibold">
            {{ optional($j?->jawaban_soal)->jawaban ?? ($j->jawaban_siswa ?? '—') }}
          </span>
        </p>
      </div>
    </div>

    {{-- Modal detail --}}
    <div class="modal fade" id="modal-soal-tf-{{ $soal->id_soal }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Soal & Jawaban Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3 text-sm text-gray-600">
              Tipe: <span class="font-semibold">{{ optional($soal->tipe_soal)->nama_tipe_soal ?? '—' }}</span> •
              Bobot: <span class="font-semibold">{{ is_null($soal->bobot) ? '—' : rtrim(rtrim(number_format((float)$soal->bobot, 2, '.', ''), '0'), '.') }}</span>
            </div>

            <div class="prose max-w-none mb-4">{!! $soal->soal !!}</div>

            <h6 class="font-semibold mb-2">Opsi Jawaban</h6>
            <ul class="space-y-2">
              @forelse($soal->jawaban_soal as $ops)
                @php
                  $teksOpsi = $ops->jawaban ?? '(kosong)';
                  $isBenar  = (int)($ops->benar ?? 0) === 1;
                  $dipilih  = $idDipilih && $idDipilih == $ops->id_jawaban_soal;
                @endphp
                <li class="p-3 rounded border {{ $isBenar ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-white' }}">
                  <div class="flex items-start justify-between">
                    <div class="mr-2">{{ $teksOpsi }}</div>
                    <div class="flex items-center gap-2">
                      @if($isBenar)
                        <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-700">Kunci</span>
                      @endif
                      @if($dipilih)
                        <span class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700">Dipilih Siswa</span>
                      @endif
                    </div>
                  </div>
                </li>
              @empty
                <li class="text-gray-500">Belum ada opsi jawaban.</li>
              @endforelse
            </ul>

            <div class="mt-4">
              <h6 class="font-semibold mb-2">Jawaban Benar</h6>
              <div class="p-3 rounded {{ $teksBenarTF ? 'border border-green-300 bg-green-50' : 'border border-gray-200 bg-gray-50' }}">
                {{ $teksBenarTF ?? 'Belum ditandai' }}
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="text-center py-6 text-gray-500">Tidak ada jawaban True/False.</div>
  @endforelse
</div>


{{-- ===== Tab Essay ===== --}}
<div class="tab-pane fade" id="essay" role="tabpanel">
  @php
    $essay = $soals->where('id_tipe_soal', 3);
  @endphp

  @forelse($essay as $soal)
    @php $j = $jawabanBySoal[$soal->id_soal] ?? null; @endphp

    {{-- Kartu ringkas --}}
    <div class="bg-gray-100 p-4 rounded-lg shadow-sm mb-4">
      <div class="flex flex-col md:flex-row justify-between md:items-start">
        <div class="flex-1">
          <h3 class="text-lg font-semibold mb-2 soal-snippet">{!! $soal->soal !!}</h3>
          <p class="text-sm text-gray-700 mt-1">
            <span class="font-semibold">Jawaban siswa:</span>
            <span class="ml-1 break-words">
              {{ $j && $j->jawaban_siswa ? Str::limit($j->jawaban_siswa, 100, '...') : '—' }}
            </span>
          </p>
        </div>

        <div class="mt-3 md:mt-0 md:ml-4">
          <button type="button"
                  class="text-yellow-500 flex items-center hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded px-3 py-1"
                  data-bs-toggle="modal" data-bs-target="#modal-soal-essay-{{ $soal->id_soal }}">
            <i class="fas fa-eye mr-1"></i> Lihat
          </button>
        </div>
      </div>
    </div>

    {{-- Modal detail --}}
    <div class="modal fade" id="modal-soal-essay-{{ $soal->id_soal }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Soal & Jawaban Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>

          <div class="modal-body">
            <div class="mb-3 text-sm text-gray-600">
              Tipe: <span class="font-semibold">{{ optional($soal->tipe_soal)->nama_tipe_soal ?? '—' }}</span> •
              Bobot: <span class="font-semibold">
                {{ is_null($soal->bobot) ? '—' : rtrim(rtrim(number_format((float)$soal->bobot, 2, '.', ''), '0'), '.') }}
              </span>
            </div>

            <div class="prose max-w-none mb-4">{!! $soal->soal !!}</div>

            <h6 class="font-semibold mb-2">Jawaban Siswa</h6>
            <div class="p-3 rounded border border-gray-200 bg-white overflow-auto"
                 style="max-height: 300px; white-space: pre-wrap; word-break: break-word;">
              {{ $j->jawaban_siswa ?? '—' }}
            </div>
          </div>

          <div class="modal-footer">
            <button type="button"
                    class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800"
                    data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

  @empty
    <div class="text-center py-6 text-gray-500">Tidak ada jawaban Essay/Isian.</div>
  @endforelse
</div>



<div class="mt-4">
  <a href="{{ route('Guru.Ujian.selesai', $ujian->id_ujian) }}"
     class="inline-flex items-center px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">
     ← Kembali
  </a>
</div>

{{-- Bootstrap JS (sama seperti halaman soal) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
