@extends('layouts.siswa')

@section('title', 'Kurikulum')

@section('breadcrumb')
  <li class="text-foreground">Kurikulum</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Kurikulum</h1>
    <p class="text-muted-foreground">Pilih kurikulum untuk melanjutkan</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($kurikulums as $k)
      <a href="{{ route('Siswa.enroll.tahunajaran', ['id_kurikulum' => $k->id_kurikulum]) }}"
         class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
            <i class="fas fa-graduation-cap text-primary-foreground"></i>
          </div>
          <div>
            <h3 class="font-semibold">{{ $k->nama_kurikulum }}</h3>
            <p class="text-sm text-muted-foreground">Klik untuk pilih</p>
          </div>
        </div>
      </a>
    @empty
      <div class="text-muted-foreground">Belum ada kurikulum</div>
    @endforelse
  </div>
@endsection
