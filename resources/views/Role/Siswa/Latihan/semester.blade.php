@extends('layouts.siswa')

@section('title', 'Semester')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.latihan.kurikulum') }}" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.latihan.tahunajaran', $kurikulum->id_kurikulum) }}" class="hover:text-foreground">Tahun Ajaran</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Semester</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-1">Semester</h1>
    <p class="text-muted-foreground">
      Kurikulum: {{ $kurikulum->nama_kurikulum }} • Tahun Ajaran: {{ $tahunAjaran->Nama_Tahun_Ajaran }}
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($semesters as $smt)
      <a href="{{ route('Siswa.latihan.mapel', [$kurikulum->id_kurikulum, $tahunAjaran->ID_Tahun_Ajaran, $smt->id_semester]) }}"
         class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-layer-group text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">{{ $smt->nama_semester }}</h3>
            <p class="text-sm text-muted-foreground">Klik untuk pilih</p>
          </div>
        </div>
      </a>
    @empty
      <div class="text-muted-foreground">Belum ada Semester</div>
    @endforelse
  </div>
@endsection
