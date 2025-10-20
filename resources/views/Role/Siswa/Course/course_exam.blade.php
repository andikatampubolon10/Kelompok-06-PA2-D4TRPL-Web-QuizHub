{{-- ========================================= --}}
{{-- resources/views/Role/Siswa/Course/course_exam.blade.php --}}
{{-- ========================================= --}}
@extends('layouts.siswa')

@section('title', 'Exams - ' . ($kursus->nama_kursus ?? 'Kursus'))

@section('breadcrumb')
  <li><a href="{{ route('Siswa.Course.index') }}" class="hover:text-foreground">My Courses</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Exams</li>
@endsection

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">{{ $kursus->nama_kursus }}</h1>
    <p class="text-muted-foreground">
      {{ optional($kursus->mataPelajaran)->nama_mata_pelajaran ?? '-' }} •
      {{ optional($kursus->kelas)->nama_kelas ?? '-' }} •
      {{ optional($kursus->guru)->nama ?? '—' }}
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Card: Materi --}}
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center">
          <i class="fas fa-book-open text-white"></i>
        </div>
        <div>
          <h3 class="font-semibold">Materi</h3>
          <p class="text-sm text-muted-foreground">Dokumen & tautan pembelajaran</p>
        </div>
      </div>

      @forelse($materi as $m)
        <div class="flex items-start justify-between rounded-lg px-3 py-3 hover:bg-secondary/40">
          <div class="pr-4">
            <p class="text-sm font-medium">{{ $m->judul_materi }}</p>
            <p class="text-xs text-muted-foreground">
              {{ $m->tanggal_materi ? \Carbon\Carbon::parse($m->tanggal_materi)->format('d M Y') : '—' }}
            </p>
            @if(!empty($m->deskripsi))
              <p class="text-sm mt-1 text-muted-foreground line-clamp-2">{{ $m->deskripsi }}</p>
            @endif
          </div>
          <div class="shrink-0 flex items-center gap-2">
            @if(!empty($m->file_url))
              <a href="{{ $m->file_url }}" target="_blank" rel="noopener"
                 class="inline-flex items-center gap-2 rounded bg-primary px-3 py-2 text-xs font-semibold text-primary-foreground hover:opacity-90">
                <i class="fas fa-link"></i> Buka
              </a>
            @elseif(!empty($m->file))
              <a href="{{ Storage::url($m->file) }}" target="_blank" rel="noopener"
                 class="inline-flex items-center gap-2 rounded bg-primary px-3 py-2 text-xs font-semibold text-primary-foreground hover:opacity-90">
                <i class="fas fa-download"></i> Unduh
              </a>
            @else
              <span class="text-xs text-muted-foreground">Tidak ada tautan</span>
            @endif
          </div>
        </div>
      @empty
        <div class="text-sm text-muted-foreground">Belum ada materi.</div>
      @endforelse
    </div>

    {{-- Card: Kuis --}}
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-question-circle text-white"></i>
        </div>
        <div>
          <h3 class="font-semibold">Kuis</h3>
          <p class="text-sm text-muted-foreground">Quick assessments</p>
        </div>
      </div>

      <div class="space-y-3">
        @forelse($quiz as $u)
          @php $isActive = ($u->status === 'Berlangsung'); @endphp
          <a href="#"
             class="btn-open-config flex items-center justify-between rounded-lg px-3 py-2 bg-secondary/40 hover:bg-secondary {{ $isActive ? '' : 'pointer-events-none opacity-50' }}"
             data-ujian-id="{{ $u->id_ujian }}"
             data-kursus-id="{{ $kursus->id_kursus }}"
             data-ujian-nama="{{ $u->nama_ujian }}">
            <div>
              <p class="text-sm font-medium">{{ $u->nama_ujian }}</p>
              <p class="text-xs text-muted-foreground">
                Mulai: {{ $u->waktu_mulai ? \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y H:i') : '—' }}
                • Durasi: {{ $u->durasi ?? '—' }} menit
                • Status: {{ $u->status ?? '—' }}
              </p>
            </div>
            <i class="fas fa-arrow-right text-muted-foreground"></i>
          </a>
        @empty
          <div class="text-sm text-muted-foreground">Belum ada kuis.</div>
        @endforelse
      </div>
    </div>

    {{-- Card: UTS --}}
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-clipboard-check text-white"></i>
        </div>
        <div>
          <h3 class="font-semibold">UTS</h3>
          <p class="text-sm text-muted-foreground">Mid-term exam</p>
        </div>
      </div>

      <div class="space-y-3">
        @forelse($uts as $u)
          @php $isActive = ($u->status === 'Berlangsung'); @endphp
          <a href="#"
             class="btn-open-config flex items-center justify-between rounded-lg px-3 py-2 bg-secondary/40 hover:bg-secondary {{ $isActive ? '' : 'pointer-events-none opacity-50' }}"
             data-ujian-id="{{ $u->id_ujian }}"
             data-kursus-id="{{ $kursus->id_kursus }}"
             data-ujian-nama="{{ $u->nama_ujian }}">
            <div>
              <p class="text-sm font-medium">{{ $u->nama_ujian }}</p>
              <p class="text-xs text-muted-foreground">
                Mulai: {{ $u->waktu_mulai ? \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y H:i') : '—' }}
                • Durasi: {{ $u->durasi ?? '—' }} menit
                • Status: {{ $u->status ?? '—' }}
              </p>
            </div>
            <i class="fas fa-arrow-right text-muted-foreground"></i>
          </a>
        @empty
          <div class="text-sm text-muted-foreground">Belum ada UTS.</div>
        @endforelse
      </div>
    </div>

    {{-- Card: UAS --}}
    <div class="bg-card rounded-lg p-6 border border-border">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
          <i class="fas fa-graduation-cap text-white"></i>
        </div>
        <div>
          <h3 class="font-semibold">UAS</h3>
          <p class="text-sm text-muted-foreground">Final exam</p>
        </div>
      </div>

      <div class="space-y-3">
        @forelse($uas as $u)
          @php $isActive = ($u->status === 'Berlangsung'); @endphp
          <a href="#"
             class="btn-open-config flex items-center justify-between rounded-lg px-3 py-2 bg-secondary/40 hover:bg-secondary {{ $isActive ? '' : 'pointer-events-none opacity-50' }}"
             data-ujian-id="{{ $u->id_ujian }}"
             data-kursus-id="{{ $kursus->id_kursus }}"
             data-ujian-nama="{{ $u->nama_ujian }}">
            <div>
              <p class="text-sm font-medium">{{ $u->nama_ujian }}</p>
              <p class="text-xs text-muted-foreground">
                Mulai: {{ $u->waktu_mulai ? \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y H:i') : '—' }}
                • Durasi: {{ $u->durasi ?? '—' }} menit
                • Status: {{ $u->status ?? '—' }}
              </p>
            </div>
            <i class="fas fa-arrow-right text-muted-foreground"></i>
          </a>
        @empty
          <div class="text-sm text-muted-foreground">Belum ada UAS.</div>
        @endforelse
      </div>
    </div>

  </div>

  {{-- Modal Konfigurasi SEB --}}
  <div id="modal-config" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative mx-auto mt-20 w-full max-w-lg rounded-lg border bg-card p-6 shadow-xl">
      <h3 class="text-lg font-semibold mb-2">Konfigurasi Safe Exam Browser</h3>
      <p class="text-sm text-muted-foreground mb-4">
        Sebelum memulai ujian, kamu harus menyetujui ketentuan berikut:
      </p>
      <ul class="list-disc list-inside text-sm space-y-1 mb-4 text-muted-foreground">
        <li>Ujian akan membuka mode layar penuh (fullscreen).</li>
        <li>Tidak boleh berpindah tab/jendela atau menggunakan shortcut.</li>
        <li>Copy, paste, klik kanan, dan cetak dinonaktifkan.</li>
        <li>Jika terdeteksi pelanggaran berulang, ujian akan dikirim otomatis.</li>
      </ul>

      <div class="flex items-center mb-4">
        <input id="chk-ready" type="checkbox" class="mr-2">
        <label for="chk-ready" class="text-sm">Saya sudah membaca dan siap memulai ujian.</label>
      </div>

      <div class="flex items-center justify-end gap-2">
        <button type="button" id="btn-config-cancel" class="rounded border px-3 py-2 text-sm">Batal</button>
        <button type="button" id="btn-config-continue"
                class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                disabled>
          Saya Setuju & Lanjut
        </button>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal   = document.getElementById('modal-config');
  const chk     = document.getElementById('chk-ready');
  const btnCont = document.getElementById('btn-config-continue');
  const btnCancel = document.getElementById('btn-config-cancel');

  let selected = { kursus: null, ujian: null, nama: null };

  document.querySelectorAll('.btn-open-config').forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      selected.kursus = el.getAttribute('data-kursus-id');
      selected.ujian  = el.getAttribute('data-ujian-id');
      selected.nama   = el.getAttribute('data-ujian-nama') || 'Ujian';
      chk.checked = false;
      btnCont.disabled = true;
      modal.classList.remove('hidden');
    });
  });

  chk.addEventListener('change', () => { btnCont.disabled = !chk.checked; });
  btnCancel.addEventListener('click', () => modal.classList.add('hidden'));

  btnCont.addEventListener('click', () => {
    if (!selected.kursus || !selected.ujian) return;
    const url = @json(route('Siswa.ujian.gate', ['kursus' => 'KURSUS_ID', 'ujian' => 'UJIAN_ID']));
    window.location.href = url.replace('KURSUS_ID', selected.kursus).replace('UJIAN_ID', selected.ujian);
  });
});
</script>
@endpush
