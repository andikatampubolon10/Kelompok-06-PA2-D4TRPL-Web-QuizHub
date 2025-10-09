@extends('layouts.siswa')

@section('title', 'Mata Pelajaran')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.latihan.kurikulum') }}" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.latihan.tahunajaran', $kurikulum->id_kurikulum) }}" class="hover:text-foreground">Tahun Ajaran</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.latihan.semester', [$kurikulum->id_kurikulum, $tahunAjaran->ID_Tahun_Ajaran]) }}" class="hover:text-foreground">Semester</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Mata Pelajaran</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-1">Mata Pelajaran</h1>
    <p class="text-muted-foreground">
      {{ $kurikulum->nama_kurikulum }} • {{ $tahunAjaran->Nama_Tahun_Ajaran }} • {{ $semester->nama_semester }}
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($mapels as $m)
      <a href="{{ route('Siswa.latihan.kelas', [$kurikulum->id_kurikulum, $tahunAjaran->ID_Tahun_Ajaran, $semester->id_semester, $m->id_mata_pelajaran]) }}"
         class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-book text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">{{ $m->nama_mata_pelajaran }}</h3>
            <p class="text-sm text-muted-foreground">Klik untuk lihat kursus</p>
          </div>
        </div>
      </a>
    @empty
      <div class="text-muted-foreground">Belum ada Mata Pelajaran</div>
    @endforelse
  </div>
@endsection
