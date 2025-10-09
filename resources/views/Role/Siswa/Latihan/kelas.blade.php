@extends('layouts.siswa')

@section('title', 'Kelas')

@section('breadcrumb')
  <li><a href="#" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="#" class="hover:text-foreground">Tahun Ajaran</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="#" class="hover:text-foreground">Semester</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="" class="hover:text-foreground">Mata Pelajaran</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Kelas</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Daftar Kelas</h1>
    <p class="text-muted-foreground">Semua kelas yang tersedia</p>
  </div>

  @if(session('success'))
    <div class="mb-4 rounded-lg border border-green-700 bg-green-900/40 px-4 py-3 text-sm">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-4 rounded-lg border border-red-700 bg-red-900/40 px-4 py-3 text-sm">
      {{ session('error') }}
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($kelas as $k)
      <a href="{{ route('Siswa.latihan.kelas.topik', [
  'id_kurikulum'      => $kurikulum->id_kurikulum,
  'id_tahun_ajaran'   => $tahunAjaran->ID_Tahun_Ajaran,
  'id_semester'       => $semester->id_semester,
  'id_mata_pelajaran' => $mapel->id_mata_pelajaran,
  'id_kelas'          => $k->id_kelas,
]) }}"
        class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
            <i class="fas fa-school text-primary-foreground"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-semibold">{{ $k->nama_kelas }}</h3>
            <p class="text-sm text-muted-foreground">
              Siswa: {{ $k->jumlah_siswa ?? 0 }} • Kursus: {{ $k->jumlah_kursus ?? 0 }}
            </p>
          </div>
        </div>

        {{-- (opsional) aksi di bagian bawah kartu --}}
        <div class="mt-4 flex items-center justify-between text-sm">
          <span class="px-2 py-1 rounded bg-secondary">Tersedia</span>
          {{-- Jika ingin diarahkan ke halaman lain berdasarkan kelas, isi route di bawah ini --}}
          {{-- <a href="{{ route('Siswa.Course.byKelas', $k->id_kelas) }}" class="hover:underline">Lihat Kursus</a> --}}
        </div>
      </a>
    @empty
      <div class="text-muted-foreground">Belum ada data kelas.</div>
    @endforelse
  </div>
@endsection
