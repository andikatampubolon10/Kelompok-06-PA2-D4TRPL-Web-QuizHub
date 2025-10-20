{{-- ========================================= --}}
{{-- resources/views/Role/Siswa/Ujian/gate.blade.php --}}
{{-- ========================================= --}}
@extends('layouts.ujian')

@section('title', 'Ujian – Gate')

@section('content')
  <div class="max-w-3xl mx-auto mt-8 bg-card border border-border rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-2">{{ $kursus->nama_kursus }}</h1>
    <p class="text-muted-foreground mb-4">
      {{ $ujian->nama_ujian }} • Status: <span class="font-semibold">{{ $status }}</span>
    </p>
    <p class="text-sm text-muted-foreground">
      Halaman ini akan mengaktifkan proteksi ujian (fullscreen, blokir shortcut, deteksi tab pindah).
      Setelah memasukkan password, kamu akan masuk ke halaman ujian.
    </p>
  </div>

  {{-- Modal Password Ujian (muncul otomatis) --}}
  <div id="modal-password" class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="relative mx-auto mt-32 w-full max-w-sm rounded-lg border bg-card p-6 shadow-xl">
      <h3 class="text-lg font-semibold mb-1">Masuk Ujian</h3>
      <p class="text-sm text-muted-foreground mb-4">Masukkan password untuk memulai.</p>

      @if(session('error'))
        <div class="mb-3 rounded bg-red-100 px-3 py-2 text-sm text-red-700">
          {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="{{ route('Siswa.Course.ujian.enter') }}">
        @csrf
        <input type="hidden" name="id_ujian"  value="{{ $ujian->id_ujian }}">
        <input type="hidden" name="id_kursus" value="{{ $kursus->id_kursus }}">

        <label class="block text-sm font-medium mb-1">Password Ujian</label>
        <input name="password" type="password" required
               class="w-full rounded border px-3 py-2 bg-black text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Ketik password…">

        <div class="flex items-center justify-end gap-2">
          <a href="{{ route('Siswa.Course.tipeujian', $kursus->id_kursus) }}" class="rounded border px-3 py-2 text-sm">Batal</a>
          <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            Masuk
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
  // Coba minta fullscreen (akan mulus jika dipicu interaksi; di sini best-effort)
  try {
    if (!document.fullscreenElement) {
      await document.documentElement.requestFullscreen({ navigationUI: 'hide' });
    }
  } catch (e) {}
  // Blok back minimal
  window.history.pushState(null,'',location.href);
  window.addEventListener('popstate', ()=>{ window.history.pushState(null,'',location.href); });
  // Blok klik kanan
  document.addEventListener('contextmenu', e => e.preventDefault());
});
</script>
@endpush
