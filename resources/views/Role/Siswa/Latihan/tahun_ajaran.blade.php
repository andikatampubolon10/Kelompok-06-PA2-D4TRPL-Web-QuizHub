@extends('layouts.siswa')

@section('title', 'Tahun Ajaran')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.latihan.kurikulum') }}" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Tahun Ajaran</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-1">Tahun Ajaran</h1>
    <p class="text-muted-foreground">Kurikulum: {{ $kurikulum->nama_kurikulum }}</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($tahunAjaran as $ta)
      <a href="{{ route('Siswa.latihan.semester', [$kurikulum->id_kurikulum, $ta->ID_Tahun_Ajaran]) }}"
         class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-calendar-alt text-white"></i>
          </div>
          <div>
            <h3 class="font-semibold">{{ $ta->Nama_Tahun_Ajaran }}</h3>
            <p class="text-sm text-muted-foreground">Klik untuk pilih</p>
          </div>
        </div>
      </a>
    @empty
      <div class="text-muted-foreground">Belum ada Tahun Ajaran</div>
    @endforelse
  </div>
@endsection
