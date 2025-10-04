@extends('layouts.siswa')

@section('title', 'Kursus')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.enroll.kurikulum') }}" class="hover:text-foreground">Kurikulum</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.enroll.tahunajaran', $kurikulum->id_kurikulum) }}" class="hover:text-foreground">Tahun Ajaran</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.enroll.semester', [$kurikulum->id_kurikulum, $tahunAjaran->ID_Tahun_Ajaran]) }}" class="hover:text-foreground">Semester</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.enroll.mapel', [$kurikulum->id_kurikulum, $tahunAjaran->ID_Tahun_Ajaran, $semester->id_semester]) }}" class="hover:text-foreground">Mata Pelajaran</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Kursus</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-1">Kursus</h1>
    <p class="text-muted-foreground">
      {{ $kurikulum->nama_kurikulum }} • {{ $tahunAjaran->Nama_Tahun_Ajaran }} • {{ $semester->nama_semester }} • {{ $mapel->nama_mata_pelajaran }}
    </p>
  </div>

  {{-- flash --}}
  @if (session('success'))
    <div class="mb-4 p-3 rounded bg-green-600/20 border border-green-600 text-green-200">
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div class="mb-4 p-3 rounded bg-red-600/20 border border-red-600 text-red-200">
      {{ session('error') }}
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($courses as $course)
      <div class="bg-card rounded-lg p-6 border border-border hover:shadow-lg transition-shadow">
        <div class="h-32 rounded-lg mb-4 overflow-hidden">
          <img src="{{ $course->image_url ?? $course->foto_url ?? 'https://placehold.co/600x400?text=Kursus' }}"
               alt="{{ $course->nama_kursus }}" class="w-full h-full object-cover">
        </div>

        <h3 class="font-semibold mb-1">{{ $course->nama_kursus }}</h3>

        <div class="text-sm text-muted-foreground space-y-1 mb-3">
          <p><span class="font-medium">Guru:</span> {{ optional($course->guru)->nama ?? '-' }}</p>
          <p><span class="font-medium">Mata Pelajaran:</span> {{ optional($course->mataPelajaran)->nama_mata_pelajaran ?? '-' }}</p>
          <p><span class="font-medium">Tahun Ajaran:</span> {{ optional($course->tahun_ajaran)->Nama_Tahun_Ajaran ?? '-' }}</p>
          <p><span class="font-medium">Kelas:</span> {{ optional($course->kelas)->nama_kelas ?? '-' }}</p>
        </div>

        <div class="flex items-center justify-between">
          @php $enrolled = in_array($course->id_kursus, $enrolledIds ?? []); @endphp

          @if($enrolled)
            <span class="text-xs bg-green-600/20 text-green-300 px-2 py-1 rounded">Enrolled</span>
            <a href="{{ route('Siswa.Course.tipeujian', ['id_kursus' => $course->id_kursus]) }}"
               class="text-sm hover:underline">Lihat Ujian</a>
          @else
            <span class="text-xs bg-secondary px-2 py-1 rounded">Available</span>
<button
  onclick='openEnrollModal({{ $course->id_kursus }}, @json($course->nama_kursus))'
  class="text-sm underline hover:no-underline">
  Enroll
</button>


          @endif
        </div>
      </div>
    @empty
      <div class="text-muted-foreground">Belum ada kursus.</div>
    @endforelse
  </div>

  {{-- Modal Enroll (single, diisi dinamis) --}}
  <div id="enrollModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60" onclick="closeEnrollModal()"></div>

    <div class="relative max-w-md mx-auto mt-24 bg-card border border-border rounded-lg p-6">
      <h3 class="text-lg font-semibold mb-2">Masuk Kursus</h3>
      <p class="text-sm text-muted-foreground mb-4">Masukkan password untuk: <span id="courseName" class="font-medium text-foreground"></span></p>

      <form method="POST" action="{{ route('Siswa.kursus.enroll.password') }}">
        @csrf
        <input type="hidden" name="id_kursus" id="courseId">
        <label class="block text-sm mb-1">Password</label>
        <input type="password" name="password"
               class="w-full border border-border rounded p-2 bg-white text-gray-900"
               autocomplete="current-password" required>

        <div class="mt-4 flex items-center justify-end gap-3">
          <button type="button" onclick="closeEnrollModal()"
                  class="px-4 py-2 rounded bg-secondary text-secondary-foreground">
            Batal
          </button>
          <button type="submit"
                  class="px-4 py-2 rounded bg-primary text-primary-foreground">
            Enroll
          </button>
        </div>
      </form>
    </div>
  </div>

@endsection

@push('scripts')
<script>
  function openEnrollModal(id, name){
    document.getElementById('courseId').value = id;
    document.getElementById('courseName').textContent = name || '';
    document.getElementById('enrollModal').classList.remove('hidden');
  }
  function closeEnrollModal(){
    document.getElementById('enrollModal').classList.add('hidden');
  }
</script>
@endpush
