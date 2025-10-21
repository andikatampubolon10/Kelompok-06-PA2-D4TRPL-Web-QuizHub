@extends('layouts.ujian')

@section('title', 'Take Exam')

@section('breadcrumb')
  <li><a href="{{ route('Siswa.Course.index') }}" class="hover:text-foreground">My Courses</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="{{ route('Siswa.Course.tipeujian', $kursus->id_kursus) }}" class="hover:text-foreground">Exams</a></li>
  <li><span class="mx-2">/</span></li>
  <li class="text-foreground">Take Exam</li>
@endsection

@section('content')
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold mb-1">{{ $kursus->nama_kursus }}</h1>
      <p class="text-muted-foreground text-white">{{ $ujian->nama_ujian }}</p>
    </div>

    <div class="flex items-center space-x-4">
      <div class="bg-card border border-border rounded-lg px-4 py-2">
        <span class="text-sm text-white">Time Left</span>
        <div id="timer" class="text-2xl font-bold tracking-widest">--:--</div>
      </div>
      <!-- Added exit button on the right side of header -->
      <button id="exitExamBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
        <i class="fas fa-sign-out-alt mr-2"></i>Exit
      </button>
      <button id="submitBtn" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:opacity-90" disabled>
        Submit
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Panel Soal -->
    <div class="lg:col-span-3">
      <div class="bg-card border border-border rounded-lg p-6">
        <div class="flex items-start justify-between mb-4">
          <div>
            <p class="text-sm text-muted-foreground">Question <span id="qIndex">1</span> / {{ $total }}</p>
            <h2 id="qTitle" class="text-lg font-semibold"></h2>
          </div>
        </div>

        <div id="choices" class="space-y-3"></div>

        <div class="mt-6 flex items-center justify-between">
          <button id="prevBtn" class="px-4 py-2 bg-secondary text-secondary-foreground rounded-lg hover:bg-muted disabled:opacity-50">
            <i class="fas fa-arrow-left mr-2"></i> Previous
          </button>
          <button id="nextBtn" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:opacity-90">
            Next <i class="fas fa-arrow-right ml-2"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Panel Navigasi Nomor Soal -->
    <div class="lg:col-span-1">
      <div class="bg-card border border-border rounded-lg p-6">
        <h3 class="font-semibold mb-3">Question Navigator</h3>
        <div id="navGrid" class="grid grid-cols-5 gap-2"></div>
        <div class="mt-4 text-xs text-muted-foreground">
          <div class="flex items-center space-x-2">
            <span class="w-3 h-3 inline-block bg-primary rounded-sm"></span>
            <span class="text-white">Current</span>
          </div>
          <div class="flex items-center space-x-2">
            <span class="w-3 h-3 inline-block bg-green-600 rounded-sm"></span>
            <span class="text-white">Answered</span>
          </div>
          <div class="flex items-center space-x-2">
            <span class="w-3 h-3 inline-block bg-border rounded-sm"></span>
            <span class="text-white">Unanswered</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Form submit (fallback) --}}
  <form id="submitForm" method="POST"
        action="{{ route('Siswa.Ujian.submit', [$kursus->id_kursus, $ujian->id_ujian]) }}">
    @csrf
    <input type="hidden" id="answers_json" name="answers_json">
  </form>

  <!-- Modal Konfirmasi Submit (custom, tidak memicu keluar fullscreen) -->
  <div id="confirmModal" class="fixed inset-0 z-[100000] hidden items-center justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <!-- Card -->
    <div role="dialog" aria-modal="true" aria-labelledby="confirmTitle"
         class="relative w-[95%] max-w-md rounded-2xl border border-border bg-card text-foreground shadow-xl">
      <div class="px-5 pt-5">
        <h4 id="confirmTitle" class="text-lg font-semibold">Kirim Jawaban?</h4>
        <p class="mt-2 text-sm text-muted-foreground">
          Pastikan semua jawaban sudah terisi. Setelah dikirim, kamu tidak bisa mengubah jawaban.
        </p>
      </div>
      <div class="p-5 flex items-center justify-end gap-3">
        <button id="cancelSubmit"
                class="px-4 py-2 rounded-lg border border-border hover:bg-secondary">
          Batal
        </button>
        <button id="confirmSubmit"
                class="px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:opacity-90">
          Kirim Sekarang
        </button>
      </div>
    </div>
  </div>

  <!-- Modal untuk password keluar ujian -->
  <div id="exitPasswordModal" class="fixed inset-0 z-[100001] hidden items-center justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <!-- Card -->
    <div role="dialog" aria-modal="true" aria-labelledby="exitTitle"
         class="relative w-[95%] max-w-md rounded-2xl border border-border bg-card text-foreground shadow-xl">
      <div class="px-5 pt-5">
        <h4 id="exitTitle" class="text-lg font-semibold">Keluar dari Ujian</h4>
        <p class="mt-2 text-sm text-muted-foreground">
          Masukkan password keluar untuk meninggalkan ujian. Jawaban Anda akan disimpan sementara.
        </p>
        <input type="password" id="exitPassword" placeholder="Masukkan password keluar" 
               class="w-full mt-4 px-3 py-2 rounded-lg border border-border bg-secondary text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary">
        <div id="exitPasswordError" class="mt-2 text-sm text-red-500 hidden"></div>
      </div>
      <div class="p-5 flex items-center justify-end gap-3">
        <button id="cancelExit"
                class="px-4 py-2 rounded-lg border border-border hover:bg-secondary">
          Batal
        </button>
        <button id="confirmExit"
                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
          Keluar
        </button>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
/* =========================
   PARAM DARI SERVER
========================= */
const QUESTIONS  = @json($questions);
const TOTAL      = {{ $total }};
const DURATION   = {{ $duration }};
const EXAM_ID    = {{ $ujian->id_ujian }};
const STUDENT_ID = {{ auth()->user()->id }};
const COURSE_ID  = {{ $kursus->id_kursus }};
const INDEX_URL  = @json(route('Siswa.Course.index'));
const SUBMIT_URL = @json(route('Siswa.Ujian.submit', [$kursus->id_kursus, $ujian->id_ujian]));
const EXIT_URL   = @json(route('Siswa.Ujian.exit', [$kursus->id_kursus, $ujian->id_ujian]));
const CSRF_TOKEN = @json(csrf_token());

const GUARD_POLICY = {
  requireFullscreen: true,
  requireWakeLock: true,
  maxViolations: 10,
  warnOnFirst: true,
  autoSubmitOnLimit: false
};

/* =========================
   STATE GLOBAL
========================= */
window.EXAM = {
  answers: Array(TOTAL).fill(null),
  current: 0,
  remaining: DURATION,
  timerId: null,

  guardActive: false,
  isSubmitting: false,
  isAutoSubmit: false,
  violationCount: 0,
  violationCooldown: false,
  beforeUnloadHandler: null,
  wakeLock: null,
  isUILocked: false
};

/* =========================
   DOM REFS
========================= */
const qIndexEl  = document.getElementById('qIndex');
const qTitleEl  = document.getElementById('qTitle');
const choicesEl = document.getElementById('choices');
const navGridEl = document.getElementById('navGrid');
const prevBtn   = document.getElementById('prevBtn');
const nextBtn   = document.getElementById('nextBtn');
const submitBtn = document.getElementById('submitBtn');
const timerEl   = document.getElementById('timer');
const exitExamBtn = document.getElementById('exitExamBtn');
const exitPasswordModal = document.getElementById('exitPasswordModal');
const exitPasswordInput = document.getElementById('exitPassword');
const exitPasswordError = document.getElementById('exitPasswordError');
const cancelExitBtn = document.getElementById('cancelExit');
const confirmExitBtn = document.getElementById('confirmExit');


/* =========================
   TIMER STORAGE FUNCTIONS
========================= */
function saveTimerToStorage() {
  const storageKey = `exam_timer_${EXAM_ID}_${STUDENT_ID}`;
  localStorage.setItem(storageKey, JSON.stringify({
    remaining: window.EXAM.remaining,
    savedAt: Date.now()
  }));
}

function restoreTimerFromStorage() {
  const storageKey = `exam_timer_${EXAM_ID}_${STUDENT_ID}`;
  const saved = localStorage.getItem(storageKey);
  if (saved) {
    try {
      const data = JSON.parse(saved);
      const elapsed = Math.floor((Date.now() - data.savedAt) / 1000);
      const newRemaining = Math.max(0, data.remaining - elapsed);
      window.EXAM.remaining = newRemaining;
      console.log('[v0] Timer restored from localStorage:', newRemaining);
      return true;
    } catch (e) {
      console.error('[v0] Error restoring timer:', e);
      return false;
    }
  }
  return false;
}

function clearTimerFromStorage() {
  const storageKey = `exam_timer_${EXAM_ID}_${STUDENT_ID}`;
  localStorage.removeItem(storageKey);
  console.log('[v0] Timer cleared from localStorage');
}

/* =========================
   STORAGE FUNCTIONS
========================= */
function saveAnswersToStorage() {
  const storageKey = `exam_answers_${EXAM_ID}_${STUDENT_ID}`;
  localStorage.setItem(storageKey, JSON.stringify(window.EXAM.answers));
  console.log('[v0] Answers saved to localStorage');
}

function restoreAnswersFromStorage() {
  const storageKey = `exam_answers_${EXAM_ID}_${STUDENT_ID}`;
  const saved = localStorage.getItem(storageKey);
  if (saved) {
    try {
      window.EXAM.answers = JSON.parse(saved);
      console.log('[v0] Answers restored from localStorage');
      return true;
    } catch (e) {
      console.error('[v0] Error restoring answers:', e);
      return false;
    }
  }
  return false;
}

function clearAnswersFromStorage() {
  const storageKey = `exam_answers_${EXAM_ID}_${STUDENT_ID}`;
  localStorage.removeItem(storageKey);
  console.log('[v0] Answers cleared from localStorage');
}

/* =========================
   BEFOREUNLOAD
========================= */

function attachBeforeUnload() {
  if (window.EXAM.beforeUnloadHandler) return;
  window.EXAM.beforeUnloadHandler = function (e) {
    if (window.EXAM.isSubmitting) return;
    saveAnswersToStorage();
    e.preventDefault();
    e.returnValue = 'Ujian sedang berlangsung. Jawaban Anda akan disimpan sementara.';
    return e.returnValue;
  };
  window.addEventListener('beforeunload', window.EXAM.beforeUnloadHandler);
}

function detachBeforeUnload() {
  if (!window.EXAM.beforeUnloadHandler) return;
  window.removeEventListener('beforeunload', window.EXAM.beforeUnloadHandler);
  window.EXAM.beforeUnloadHandler = null;
}

/* =========================
   RENDER SOAL
========================= */
const TYPE = { PG: 1, TF: 2, ISIAN: 3 };
function getQType(q) {
  if (q.tipe_id === TYPE.PG) return 'pg';
  if (q.tipe_id === TYPE.TF) return 'tf';
  if (q.tipe_id === TYPE.ISIAN) return 'isian';
  return 'pg';
}
function updateSubmitAvailability() {
  const anyAnswered = window.EXAM.answers.some(v => v !== null && String(v).trim() !== '');
  submitBtn.disabled = !anyAnswered;
}
function renderQuestion(idx) {
  const q = QUESTIONS[idx];
  const t = getQType(q);
  qIndexEl.textContent = idx + 1;
  qTitleEl.textContent = q.text;
  choicesEl.innerHTML = '';

  if (t === 'pg') {
    const letters = ['A','B','C','D','E','F','G'];
    (q.choices || []).forEach((choiceText, i) => {
      const optKey = letters[i] || String.fromCharCode(65+i);
      const wrap = document.createElement('label');
      wrap.className = 'flex items-center gap-3 p-3 border border-border rounded-lg hover:bg-secondary cursor-pointer';
      const input = document.createElement('input');
      input.type = 'radio';
      input.name = `q_${q.id}`;
      input.value = optKey;
      input.checked = (window.EXAM.answers[idx] === optKey);
      input.addEventListener('change', () => { window.EXAM.answers[idx] = optKey; renderNavigator(); updateSubmitAvailability(); saveAnswersToStorage(); });
      const badge = document.createElement('span');
      badge.className = 'inline-flex items-center justify-center w-8 h-8 rounded-md border border-border text-sm font-semibold';
      badge.textContent = optKey;
      const text = document.createElement('span');
      text.textContent = choiceText;
      wrap.append(input,badge,text);
      choicesEl.appendChild(wrap);
    });
  } else if (t === 'tf') {
    const list = (q.choices && q.choices.length) ? q.choices.slice(0,2) : ['True','False'];
    const mapKey = (txt)=> (/^t(rue)?|^b(enar)?|^ya/i.test(txt)?'T':'F');
    list.forEach((txt)=>{
      const key = mapKey(String(txt).trim());
      const wrap = document.createElement('label');
      wrap.className = 'flex items-center gap-3 p-3 border border-border rounded-lg hover:bg-secondary cursor-pointer';
      const input = document.createElement('input');
      input.type = 'radio';
      input.name = `q_${q.id}`;
      input.value = key;
      input.checked = (window.EXAM.answers[idx] === key);
      input.addEventListener('change', () => { window.EXAM.answers[idx] = key; renderNavigator(); updateSubmitAvailability(); saveAnswersToStorage(); });
      const badge = document.createElement('span');
      badge.className = 'inline-flex items-center justify-center w-10 h-8 rounded-md border border-border text-sm font-semibold';
      badge.textContent = key;
      const text = document.createElement('span');
      text.textContent = (key==='T'?'True':'False');
      wrap.append(input,badge,text);
      choicesEl.appendChild(wrap);
    });
  } else {
    const area = document.createElement('textarea');
    area.rows = 4;
    area.placeholder = 'Type your answer here…';
    area.className = 'w-full rounded border border-border bg-white text-gray-900 placeholder-gray-500 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500';
    area.value = (window.EXAM.answers[idx] ?? '');
    area.addEventListener('input', () => { window.EXAM.answers[idx] = area.value.trim(); renderNavigator(); updateSubmitAvailability(); saveAnswersToStorage(); });
    choicesEl.appendChild(area);
  }
}
function renderNavigator() {
  navGridEl.innerHTML = '';
  for (let i=0;i<TOTAL;i++){
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'w-10 h-10 rounded-md border text-sm';
    btn.textContent = i+1;
    const isCurrent  = (i === window.EXAM.current);
    const ans        = window.EXAM.answers[i];
    const answered   = (ans !== null && String(ans).trim() !== '');
    if (isCurrent)      btn.classList.add('bg-primary','text-primary-foreground','border-primary');
    else if (answered)  btn.classList.add('bg-green-600','text-white','border-green-600');
    else                btn.classList.add('border-border','text-foreground','bg-transparent','hover:bg-secondary');
    btn.addEventListener('click', ()=>{ window.EXAM.current = i; renderQuestion(window.EXAM.current); renderNavigator(); updatePrevNext(); updateSubmitAvailability(); });
    navGridEl.appendChild(btn);
  }
}
function updatePrevNext() {
  prevBtn.disabled = (window.EXAM.current === 0);
  nextBtn.textContent = (window.EXAM.current === TOTAL-1) ? 'Finish' : 'Next';
}

/* =========================
   TIMER
========================= */
function updateTimerUI() {
  const m = Math.floor(window.EXAM.remaining/60).toString().padStart(2,'0');
  const s = (window.EXAM.remaining%60).toString().padStart(2,'0');
  timerEl.textContent = `${m}:${s}`;
}

function startTimer() {
  updateTimerUI();
  window.EXAM.timerId = setInterval(()=>{
    window.EXAM.remaining--;
    // <CHANGE> Save timer to localStorage every second
    saveTimerToStorage();
    
    if (window.EXAM.remaining <= 0) {
      window.EXAM.remaining = 0;
      clearInterval(window.EXAM.timerId);
      clearTimerFromStorage();
      window.doSubmit(true);
      return;
    }
    updateTimerUI();
  }, 1000);
}

/* =========================
   MODAL KONFIRMASI (custom)
========================= */
const confirmModal     = document.getElementById('confirmModal');
const cancelSubmitEl   = document.getElementById('cancelSubmit');
const confirmSubmitEl  = document.getElementById('confirmSubmit');

function openConfirmModal() {
  confirmModal.classList.remove('hidden');
  confirmModal.classList.add('flex');
}
function closeConfirmModal() {
  confirmModal.classList.add('hidden');
  confirmModal.classList.remove('flex');
}
confirmModal.addEventListener('click', (e) => {
  if (e.target === confirmModal) closeConfirmModal();
});

cancelSubmitEl.addEventListener('click', () => closeConfirmModal());
confirmSubmitEl.addEventListener('click', async () => {
  closeConfirmModal();
  await window.doSubmit(true);
});

/* =========================
   EXIT MODAL FUNCTIONS
========================= */
function openExitPasswordModal() {
  exitPasswordInput.value = '';
  exitPasswordError.classList.add('hidden');
  exitPasswordModal.classList.remove('hidden');
  exitPasswordModal.classList.add('flex');
  exitPasswordInput.focus();
}

function closeExitPasswordModal() {
  exitPasswordModal.classList.add('hidden');
  exitPasswordModal.classList.remove('flex');
  exitPasswordInput.value = '';
  exitPasswordError.classList.add('hidden');
}

async function handleExitPassword() {
  const enteredPassword = exitPasswordInput.value.trim();
  
  if (!enteredPassword) {
    exitPasswordError.textContent = 'Password tidak boleh kosong';
    exitPasswordError.classList.remove('hidden');
    return;
  }

  // Save answers before sending request
  saveAnswersToStorage();

  try {
    const response = await fetch(EXIT_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        password_keluar: enteredPassword
      }),
      credentials: 'same-origin'
    });

    const data = await response.json();

    if (response.ok && data.status === 'success') {
      // Password benar - redirect ke course index
      window.EXAM.isSubmitting = true;
      window.EXAM.guardActive = false;
      detachBeforeUnload();
      closeExitPasswordModal();
      window.location.href = data.redirect;
    } else {
      // Password salah - tampilkan error
      exitPasswordError.textContent = data.message || 'Password salah. Coba lagi.';
      exitPasswordError.classList.remove('hidden');
      exitPasswordInput.value = '';
      exitPasswordInput.focus();
    }
  } catch (error) {
    console.error('[v0] Error during exit:', error);
    exitPasswordError.textContent = 'Terjadi kesalahan. Coba lagi.';
    exitPasswordError.classList.remove('hidden');
  }
}

exitExamBtn.addEventListener('click', () => {
  openExitPasswordModal();
});

cancelExitBtn.addEventListener('click', () => {
  closeExitPasswordModal();
});

confirmExitBtn.addEventListener('click', () => {
  handleExitPassword();
});

exitPasswordInput.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') {
    handleExitPassword();
  }
});

exitPasswordModal.addEventListener('click', (e) => {
  if (e.target === exitPasswordModal) {
    closeExitPasswordModal();
  }
});

/* =========================
   SUBMIT (via fetch + fallback)
========================= */
window.doSubmit = async function(skipConfirm = true){
  if (window.EXAM.isSubmitting) return;

  window.EXAM.isSubmitting = true;
  window.EXAM.guardActive  = false;
  detachBeforeUnload();

  const payload = QUESTIONS.map((q,i)=>{
    const t   = getQType(q);
    const ans = window.EXAM.answers[i];
    let choiceId = null;
    if ((t==='pg' || t==='tf') && q.choice_ids) {
      choiceId = q.choice_ids[String(ans)] ?? null;
    }
    return {
      id_soal: q.id,
      jawaban_siswa: (ans==null?'':String(ans)),
      id_jawaban_soal: choiceId
    };
  });

  try {
    const res = await fetch(SUBMIT_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ answers_json: payload }),
      credentials: 'same-origin'
    });

    if (res.ok) {
      const ct = res.headers.get('Content-Type') || '';
      if (ct.includes('application/json')) {
        const data = await res.json().catch(()=> ({}));
        if (data.redirect) { 
          clearAnswersFromStorage();
          clearTimerFromStorage(); // <CHANGE> Clear timer when submitted
          window.location.href = data.redirect; 
          return; 
        }
      }
      clearAnswersFromStorage();
      clearTimerFromStorage(); // <CHANGE> Clear timer when submitted
      window.location.href = INDEX_URL;
      return;
    }else {
      console.warn('Submit failed with status:', res.status);
    }
  } catch (err) {
    console.error('Fetch submit error:', err);
  }

  try {
    document.getElementById('answers_json').value = JSON.stringify(payload);
    document.getElementById('submitForm').submit();
  } catch (e) {
    console.error('Form submit fallback error:', e);
    alert('Gagal mengirim jawaban. Muat ulang halaman lalu coba lagi.');
    window.EXAM.isSubmitting = false;
    window.EXAM.guardActive  = true;
    attachBeforeUnload();
  }
};

/* =========================
   EXAM GUARD (disable Alt+Tab via lock UI)
========================= */
(function Guard(){
  const warnBanner = document.createElement('div');
  Object.assign(warnBanner.style, {
    position:'fixed', top:'0', left:'0', right:'0', zIndex:'99999',
    display:'none', padding:'10px 14px', background:'#7c2d12', color:'#fff',
    fontSize:'14px', textAlign:'center', pointerEvents:'none'
  });
  warnBanner.innerText='Peringatan: Tetap berada di tampilan ujian.';
  document.body.appendChild(warnBanner);
  
  function showWarn(msg){ 
    warnBanner.innerText = msg || 'Peringatan: Tetap berada di tampilan ujian.'; 
    warnBanner.style.display='block'; 
    setTimeout(()=>warnBanner.style.display='none',3000); 
  }

  let violationCount = 0;
  const maxViolations = GUARD_POLICY.maxViolations;

  async function requestFullscreenLoop(){
    if (!GUARD_POLICY.requireFullscreen) return true;
    try {
      if (!document.fullscreenElement) {
        await document.documentElement.requestFullscreen({navigationUI:'hide'});
      }
      return true;
    } catch { return false; }
  }
  async function requestWakeLock(){
    if ('wakeLock' in navigator && GUARD_POLICY.requireWakeLock) {
      try { window.EXAM.wakeLock = await navigator.wakeLock.request('screen'); } catch {}
    }
  }

  const blocker = document.createElement('div');
  Object.assign(blocker.style, {
    position: 'fixed', 
    inset: '0', 
    zIndex: '99998',
    background: 'rgba(0,0,0,0.6)', 
    display: 'none',
    alignItems: 'center', 
    justifyContent: 'center', 
    backdropFilter: 'blur(2px)',
    pointerEvents: 'auto'
  });

  const card = document.createElement('div');
  Object.assign(card.style, {
    background: '#0b1220',
    border: '1px solid #1f2a44',
    borderRadius: '12px',
    padding: '20px',
    maxWidth: '520px',
    width: '92%',
    color: '#fff',
    textAlign: 'center',
    zIndex: '99999',
    position: 'relative'
  });

  card.innerHTML = `
    <div style="font-weight:700; font-size:18px; margin-bottom:8px">Fokus Ujian Terputus</div>
    <div style="font-size:14px; opacity:.9; margin-bottom:16px">
      Kamu keluar dari jendela ujian (Alt+Tab/pindah aplikasi). Untuk melanjutkan, klik tombol di bawah.
    </div>
    <button id="resumeExamBtn"
      style="padding:10px 16px; border-radius:10px; border:1px solid #2563eb; background:#2563eb; color:#fff; font-weight:600; cursor:pointer; pointer-events:auto; z-index:100000; position:relative;">
      Lanjutkan Ujian
    </button>
  `;
  blocker.appendChild(card);
  document.body.appendChild(blocker);

  function setUIDisabled(on) {
    blocker.style.display = on ? 'flex' : 'none';
    window.EXAM.isUILocked = on;
  }

  blocker.addEventListener('click', (e) => {
    if (e.target === blocker) {
      e.preventDefault();
    }
  });

  setTimeout(() => {
    const resumeBtn = document.getElementById('resumeExamBtn');
    if (resumeBtn) {
      resumeBtn.addEventListener('click', async () => {
        if (GUARD_POLICY.requireFullscreen && !document.fullscreenElement) {
          try {
            await document.documentElement.requestFullscreen();
          } catch (err) {
            console.error('Error requesting fullscreen: ', err);
          }
        }
        setUIDisabled(false);
        showWarn('Fokus kembali ke ujian.');
      });
    }
  }, 0);

  window.history.pushState(null,'',location.href);
  window.addEventListener('popstate', ()=>{ 
    window.history.pushState(null,'',location.href); 
    showWarn('Navigasi kembali dinonaktifkan.'); 
  });

  document.addEventListener('contextmenu', e=>e.preventDefault());
  document.addEventListener('dragstart',  e=>e.preventDefault());
  document.addEventListener('selectstart', e=>{ 
    const t = e.target; 
    if (t && ((t.tagName==='TEXTAREA') || (t.tagName==='INPUT' && t.type==='text'))) return; 
    e.preventDefault(); 
  });
  
  ['copy','cut','paste'].forEach(ev=>document.addEventListener(ev, e=>{ 
    const t = e.target; 
    if (t && ((t.tagName==='TEXTAREA') || (t.tagName==='INPUT' && t.type==='text'))) return; 
    e.preventDefault();
    if (ev === 'copy') showWarn('Copy dinonaktifkan selama ujian.');
    if (ev === 'cut') showWarn('Cut dinonaktifkan selama ujian.');
    if (ev === 'paste') showWarn('Paste dinonaktifkan selama ujian.');
  }));

  const BLOCK_KEYS = new Set(['F12','F11','PrintScreen']);
  const CTRL_COMBOS = ['KeyT','KeyW','KeyN','KeyL','KeyK','KeyP','KeyS','KeyR','Equal','Minus','Digit0'];
  
  document.addEventListener('keydown', (e)=>{
    if (BLOCK_KEYS.has(e.key)) { 
      e.preventDefault(); 
      e.stopPropagation(); 
      if (e.key==='F11') showWarn('Jangan keluar fullscreen.'); 
      return false; 
    }
    
    if ((e.ctrlKey || e.metaKey) && (e.code === 'KeyC' || e.code === 'KeyV')) {
      e.preventDefault();
      e.stopPropagation();
      showWarn(e.code === 'KeyC' ? 'CTRL+C dinonaktifkan.' : 'CTRL+V dinonaktifkan.');
      return false;
    }
    
    if (e.ctrlKey || e.metaKey) {
      if (CTRL_COMBOS.includes(e.code)) { 
        e.preventDefault(); 
        e.stopPropagation(); 
        showWarn('Shortcut dinonaktifkan.'); 
        return false; 
      }
      if (e.shiftKey && ['KeyI','KeyJ','KeyC'].includes(e.code)) { 
        e.preventDefault(); 
        e.stopPropagation(); 
        showWarn('DevTools dinonaktifkan.'); 
        return false; 
      }
      if (e.code === 'KeyU') { 
        e.preventDefault(); 
        e.stopPropagation(); 
        showWarn('View source dinonaktifkan.'); 
        return false; 
      }
    }
    if (e.altKey && (e.code === 'ArrowLeft' || e.code === 'ArrowRight')) {
      e.preventDefault(); 
      e.stopPropagation(); 
      showWarn('Navigasi browser dinonaktifkan.'); 
      return false;
    }
  }, {capture:true});

  function violation(reason){
    if (!window.EXAM.guardActive || window.EXAM.isSubmitting) return;

    violationCount++;
    const remaining = maxViolations - violationCount;

    if (violationCount < maxViolations) {
      showWarn(`${reason} Kesempatan tersisa: ${remaining}`);
    }

    if (violationCount >= maxViolations) {
      showWarn('Batas pelanggaran tercapai. UI tetap dikunci.');
      setUIDisabled(true);
    }
  }

  document.addEventListener('visibilitychange', () => {
    if (!window.EXAM.guardActive || window.EXAM.isSubmitting) return;

    if (document.hidden) {
      violationCount++;
      const remaining = maxViolations - violationCount;

      if (violationCount < maxViolations) {
        showWarn(`Berpindah dari tab/jendela. Kesempatan tersisa: ${remaining}`);
        setUIDisabled(true);
      } else {
        setUIDisabled(true);
        showWarn('Batas pelanggaran tercapai. UI dikunci permanen.');
      }
    }
  });

  window.addEventListener('blur', ()=> {
    if (!window.EXAM.guardActive || window.EXAM.isSubmitting) return;
    setUIDisabled(true);
    showWarn('Fokus keluar dari jendela ujian. UI dikunci.');
    violation('Fokus keluar dari jendela ujian.');
  });

  window.addEventListener('focus', ()=>{ /* tetap terkunci sampai klik "Lanjutkan Ujian" */ });

  window.addEventListener('pagehide', ()=>{
    if (!window.EXAM.guardActive || window.EXAM.isSubmitting) return;
    setUIDisabled(true);
    showWarn('Halaman disembunyikan / pindah konteks. UI dikunci.');
    violation('Halaman disembunyikan / pindah konteks.');
  });

  document.addEventListener('fullscreenchange', ()=>{
    if (window.EXAM.guardActive && GUARD_POLICY.requireFullscreen && !document.fullscreenElement){
      showWarn('Tetap dalam mode layar penuh.');
      setUIDisabled(true);
      violation('Keluar fullscreen.');
    }
  });

  async function startGuard(){
    if (GUARD_POLICY.requireFullscreen) {
      const ok = await requestFullscreenLoop();
      if (!ok) alert('Izinkan fullscreen untuk memulai ujian.');
    }
    await requestWakeLock();
    attachBeforeUnload();
    window.EXAM.guardActive = true;
    showWarn('Mode Ujian aktif. Jangan berpindah tab/jendela.');
  }

  startGuard();
})();


/* =========================
   INISIALISASI UI
========================= */
restoreAnswersFromStorage();
restoreTimerFromStorage();

renderQuestion(window.EXAM.current);
renderNavigator();
updatePrevNext();
updateSubmitAvailability();

submitBtn.type = 'button';
submitBtn.addEventListener('click', () => {
  openConfirmModal();
});

prevBtn.addEventListener('click', ()=>{
  if (window.EXAM.current>0){
    window.EXAM.current--;
    renderQuestion(window.EXAM.current); renderNavigator(); updatePrevNext(); updateSubmitAvailability();
  }
});
nextBtn.addEventListener('click', ()=>{
  if (window.EXAM.current < TOTAL-1){
    window.EXAM.current++;
    renderQuestion(window.EXAM.current); renderNavigator(); updatePrevNext(); updateSubmitAvailability();
  } else {
    openConfirmModal();
  }
});

startTimer();
</script>
@endpush
