@extends('layouts.siswa')

@section('title', 'Topik Latihan - ' . ($kelas->nama_kelas ?? 'Kelas'))

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
    <h1 class="text-2xl font-bold mb-1">Topik Latihan</h1>
    <p class="text-muted-foreground">
      Kurikulum: {{ $kurikulum->nama_kurikulum }} • Tahun Ajaran: {{ $tahunAjaran->Nama_Tahun_Ajaran }}
    </p>
  </div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  @forelse($topiks as $t)
    <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow">
      <h3 class="font-semibold mb-1">{{ $t->Topik }}</h3>
      <p class="text-sm text-muted-foreground mb-2">
        Mapel: {{ optional($t->mataPelajaran)->nama_mata_pelajaran ?? '-' }}<br>
        Grade: {{ $t->grade ?? '-' }} • Acak: {{ $t->acak ?? '-' }} • Jawaban: {{ $t->status_jawaban ?? '-' }}<br>
        Guru: {{ optional($t->guru)->nama ?? '-' }}
      </p>

      <div class="flex items-center justify-between">
        <span class="text-xs bg-secondary px-2 py-1 rounded">Tersedia</span>
        <a href="{{ route('Siswa.latihan.kelas.topik.detail', [
          $kurikulum->id_kurikulum,
          $tahunAjaran->ID_Tahun_Ajaran,
          $semester->id_semester,
          $mapel->id_mata_pelajaran,
          $kelas->id_kelas,
          $t->id_latihan
        ]) }}" class="text-sm hover:underline">Mulai</a>
      </div>
    </div>
  @empty
    <div class="text-muted-foreground">Belum ada topik untuk kelas ini.</div>
  @endforelse
</div>

@endsection
