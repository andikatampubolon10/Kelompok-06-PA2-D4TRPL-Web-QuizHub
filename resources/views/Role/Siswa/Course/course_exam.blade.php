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

    {{-- Card: Quiz --}}
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
        <a href="#"
          class="btn-open-ujian flex items-center justify-between bg-secondary/40 hover:bg-secondary rounded-lg px-3 py-2"
          data-ujian-id="{{ $u->id_ujian }}"
          data-kursus-id="{{ $kursus->id_kursus }}"
          data-ujian-nama="{{ $u->nama_ujian }}">
          <div>
            <p class="text-sm font-medium">{{ $u->nama_ujian }}</p>
            <p class="text-xs text-muted-foreground">
              Mulai: {{ $u->waktu_mulai ? \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y H:i') : '—' }}
              • Durasi: {{ $u->durasi ?? '—' }} menit
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
        <a href="#"
          class="btn-open-ujian flex items-center justify-between bg-secondary/40 hover:bg-secondary rounded-lg px-3 py-2"
          data-ujian-id="{{ $u->id_ujian }}"
          data-kursus-id="{{ $kursus->id_kursus }}"
          data-ujian-nama="{{ $u->nama_ujian }}">
          <div>
            <p class="text-sm font-medium">{{ $u->nama_ujian }}</p>
            <p class="text-xs text-muted-foreground">
              Mulai: {{ $u->waktu_mulai ? \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y H:i') : '—' }}
              • Durasi: {{ $u->durasi ?? '—' }} menit
            </p>
          </div>
          <i class="fas fa-arrow-right text-muted-foreground"></i>
        </a>
      @empty
        <div class="text-sm text-muted-foreground">Belum ada kuis.</div>
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
          <a href="#"
            class="btn-open-ujian flex items-center justify-between bg-secondary/40 hover:bg-secondary rounded-lg px-3 py-2"
            data-ujian-id="{{ $u->id_ujian }}"
            data-kursus-id="{{ $kursus->id_kursus }}"
            data-ujian-nama="{{ $u->nama_ujian }}">
            <div>
              <p class="text-sm font-medium">{{ $u->nama_ujian }}</p>
              <p class="text-xs text-muted-foreground">
                Mulai: {{ $u->waktu_mulai ? \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y H:i') : '—' }}
                • Durasi: {{ $u->durasi ?? '—' }} menit
              </p>
            </div>
            <i class="fas fa-arrow-right text-muted-foreground"></i>
          </a>
        @empty
          <div class="text-sm text-muted-foreground">Belum ada kuis.</div>
        @endforelse
      </div>
    </div>

  </div>

  {{-- Modal Password Ujian --}}
<div id="modal-ujian" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/50"></div>

  <div class="relative mx-auto mt-24 w-full max-w-sm rounded-lg border bg-black p-5 shadow-xl">
    <div class="mb-3">
      <h3 class="text-lg font-semibold">Masuk Ujian</h3>
      <p class="text-sm text-muted-foreground" id="modal-ujian-title">Masukkan password untuk memulai ujian.</p>
    </div>

    @if(session('error'))
      <div class="mb-2 rounded bg-red-100 px-3 py-2 text-sm text-red-700">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('Siswa.Course.ujian.enter') }}">
      @csrf
      <input type="hidden" name="id_ujian" id="modal-id-ujian">
      <input type="hidden" name="id_kursus" id="modal-id-kursus">

      <label class="block text-sm font-medium mb-1" for="modal-password">Password Ujian</label>

      <div class="relative">
        <input
          id="modal-password"
          type="password"            {{-- akan ditoggle ke "text" --}}
          name="password"
          class="w-full rounded border border-gray-300 pr-10 px-3 py-2 bg-black text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Ketik password…"
          required
        />
        {{-- Tombol eye --}}
        <button type="button"
                id="toggle-password"
                class="absolute inset-y-0 right-2 my-auto h-7 px-2 rounded text-gray-500 hover:text-gray-700 focus:outline-none"
                aria-label="Tampilkan/sembunyikan password"
                aria-pressed="false">
          {{-- Pakai ikon Font Awesome kalau ada, fallback teks 👁 --}}
          <i class="far fa-eye" id="eye-open"></i>
          <i class="far fa-eye-slash hidden" id="eye-closed"></i>
        </button>
      </div>

      <div class="mt-4 flex items-center justify-end gap-2">
        <button type="button" id="btn-cancel-ujian"
                class="rounded border px-3 py-2 text-sm">
          Batal
        </button>
        <button type="submit"
                class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
          Masuk
        </button>
      </div>

    </form>
  </div>
</div>

{{-- Script Modal --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal      = document.getElementById('modal-ujian');
    const titleEl    = document.getElementById('modal-ujian-title');
    const idUjianEl  = document.getElementById('modal-id-ujian');
    const idKursusEl = document.getElementById('modal-id-kursus');
    const pwdEl      = document.getElementById('modal-password');
    const btnCancel  = document.getElementById('btn-cancel-ujian');
    const btnToggle = document.getElementById('toggle-password');
    const eyeOpen   = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    const chkShow   = document.getElementById('chk-show-password');

    // open modal
    document.querySelectorAll('.btn-open-ujian').forEach(el => {
      el.addEventListener('click', (e) => {
        e.preventDefault();
        const idUjian  = el.getAttribute('data-ujian-id');
        const idKursus = el.getAttribute('data-kursus-id');
        const namaUji  = el.getAttribute('data-ujian-nama') || 'Ujian';

        idUjianEl.value  = idUjian;
        idKursusEl.value = idKursus;
        titleEl.textContent = `Masukkan password untuk "${namaUji}"`;
        pwdEl.value = '';
        modal.classList.remove('hidden');
        setTimeout(() => pwdEl.focus(), 50);
      });
    });

    function setVisible(visible) {
      pwdEl.type = visible ? 'text' : 'password';
      if (eyeOpen && eyeClosed) {
        eyeOpen.classList.toggle('hidden', visible);
        eyeClosed.classList.toggle('hidden', !visible);
      }
      if (btnToggle) btnToggle.setAttribute('aria-pressed', String(visible));
    }

    if (btnToggle) {
      btnToggle.addEventListener('click', () => {
        setVisible(pwdEl.type === 'password'); // toggle
        pwdEl.focus();
      });
    }

    if (chkShow) {
      chkShow.addEventListener('change', (e) => {
        setVisible(e.target.checked);
        pwdEl.focus();
      });
    }

    // close modal
    function closeModal() {
      modal.classList.add('hidden');
    }
    btnCancel.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });
  });
</script>

@endsection
