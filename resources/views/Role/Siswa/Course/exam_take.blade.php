{{-- ========================================= --}}
{{-- resources/views/Role/Siswa/Ujian/take_exam.blade.php --}}
{{-- ========================================= --}}
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
            <p class="text-sm text-muted-foreground">Question <span id="qIndex">1</span> / </p>
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

  {{-- Form submit --}}
  <form id="submitForm" method="POST"
        action="{{ route('Siswa.Ujian.submit', [$kursus->id_kursus, $ujian->id_ujian]) }}">
    @csrf
    <input type="hidden" id="answers_json" name="answers_json">
  </form>
@endsection

@push('scripts')
<script>
/* =========================
   PARAM DARI SERVER
========================= */
const QUESTIONS = @json($questions);          // [{id,text,tipe_id,choices?,choice_ids?}, ...]
const TOTAL     = {{ $total }};
const DURATION  = {{ $duration }};            // detik
const EXAM_ID   = {{ $ujian->id_ujian }};
const STUDENT_ID= {{ auth()->user()->id }};
const COURSE_ID = {{ $kursus->id_kursus }};

const GUARD_POLICY = {
  requireFullscreen: true,
  requireWakeLock: true,
  maxViolations: 2,
  warnOnFirst: true,
  autoSubmitOnLimit: true
};

/* =========================
   STATE GLOBAL / NAMESPACE
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
  LOCK_KEY: `exam_lock_${EXAM_ID}`,
  bc: ('BroadcastChannel' in window) ? new BroadcastChannel(`exam_channel_${EXAM_ID}`) : null
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

/* =========================
   BEFOREUNLOAD
========================= */
function attachBeforeUnload() {
  if (window.EXAM.beforeUnloadHandler) return;
  window.EXAM.beforeUnloadHandler = function (e) {
    if (window.EXAM.isSubmitting) return;
    e.preventDefault();
    e.returnValue = 'Ujian sedang berlangsung. Yakin ingin keluar? Jawabanmu bisa hilang.';
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
      input.addEventListener('change', () => { window.EXAM.answers[idx] = optKey; renderNavigator(); updateSubmitAvailability(); });
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
      input.addEventListener('change', () => { window.EXAM.answers[idx] = key; renderNavigator(); updateSubmitAvailability(); });
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
    area.addEventListener('input', () => { window.EXAM.answers[idx] = area.value.trim(); renderNavigator(); updateSubmitAvailability(); });
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
    if (window.EXAM.remaining <= 0) {
      window.EXAM.remaining = 0;
      clearInterval(window.EXAM.timerId);
      window.doSubmit(true); // auto submit saat waktu habis
      return;
    }
    updateTimerUI();
  }, 1000);
}

/* =========================
   SUBMIT (GLOBAL)
========================= */
window.doSubmit = function(skipConfirm=false){
  if (window.EXAM.isSubmitting) return;
  window.EXAM.isSubmitting = true;
  window.EXAM.guardActive  = false;
  detachBeforeUnload();

  if (!skipConfirm) {
    const ok = confirm('Are you sure you want to submit the exam?');
    if (!ok) {
      window.EXAM.isSubmitting = false;
      window.EXAM.guardActive  = true;
      attachBeforeUnload();
      return;
    }
  }

  const payload = QUESTIONS.map((q,i)=>{
    const t = getQType(q);
    const ans = window.EXAM.answers[i];
    let choiceId = null;
    if ((t==='pg' || t==='tf') && q.choice_ids) {
      choiceId = q.choice_ids[String(ans)] ?? null;
    }
    return { id_soal: q.id, jawaban_siswa: (ans==null?'':String(ans)), id_jawaban_soal: choiceId };
  });
  document.getElementById('answers_json').value = JSON.stringify(payload);
  document.getElementById('submitForm').submit();
};

/* =========================
   EXAM GUARD
========================= */
(function Guard(){
  // Banner peringatan (tidak menghalangi klik)
  const warnBanner = document.createElement('div');
  Object.assign(warnBanner.style, {
    position:'fixed', top:'0', left:'0', right:'0', zIndex:'99999',
    display:'none', padding:'10px 14px', background:'#7c2d12', color:'#fff',
    fontSize:'14px', textAlign:'center', pointerEvents:'none'
  });
  warnBanner.innerText='Peringatan: Tetap berada di tampilan ujian.';
  document.body.appendChild(warnBanner);
  function showWarn(msg){ warnBanner.innerText = msg || 'Peringatan: Tetap berada di tampilan ujian.'; warnBanner.style.display='block'; setTimeout(()=>warnBanner.style.display='none',3000); }

  async function requestFullscreenLoop(){
    if (!GUARD_POLICY.requireFullscreen) return true;
    try { if (!document.fullscreenElement) await document.documentElement.requestFullscreen({navigationUI:'hide'}); return true; }
    catch { return false; }
  }
  async function requestWakeLock(){ if ('wakeLock' in navigator && GUARD_POLICY.requireWakeLock) { try { window.EXAM.wakeLock = await navigator.wakeLock.request('screen'); } catch {} } }

  function notifyChannel(type,data={}){ if(window.EXAM.bc) window.EXAM.bc.postMessage({type,data}); }
  function claimExamLock(){
    const now = Date.now();
    try{
      if(localStorage.getItem(window.EXAM.LOCK_KEY)){ notifyChannel('lock-contested'); violation('Terdeteksi lebih dari satu tab ujian.'); return false; }
      localStorage.setItem(window.EXAM.LOCK_KEY, JSON.stringify({by:STUDENT_ID, at:now}));
      window.addEventListener('beforeunload', ()=>{ try{localStorage.removeItem(window.EXAM.LOCK_KEY);}catch{}; notifyChannel('leaving'); });
      return true;
    }catch{ return true; }
  }
  if (window.EXAM.bc){
    window.EXAM.bc.onmessage = (ev)=>{ if((ev.data||{}).type === 'lock-contested') violation('Lebih dari satu tab ujian terdeteksi.'); };
  }

  // Cegah back
  window.history.pushState(null,'',location.href);
  window.addEventListener('popstate', ()=>{ window.history.pushState(null,'',location.href); showWarn('Navigasi kembali dinonaktifkan.'); });

  // Blok klik kanan/seleksi/drag/copy/cut/paste
  document.addEventListener('contextmenu', e=>e.preventDefault());
  document.addEventListener('dragstart',  e=>e.preventDefault());
  document.addEventListener('selectstart',e=>e.preventDefault());
  ['copy','cut','paste'].forEach(ev=>document.addEventListener(ev, e=>e.preventDefault()));

  // Blok shortcut umum
  const BLOCK_KEYS = new Set(['F12','F11','PrintScreen']);
  const CTRL_COMBOS = ['KeyT','KeyW','KeyN','KeyL','KeyK','KeyP','KeyS','KeyR','Equal','Minus','Digit0'];
  document.addEventListener('keydown', (e)=>{
    if (BLOCK_KEYS.has(e.key)) { e.preventDefault(); e.stopPropagation(); if (e.key==='F11') showWarn('Jangan keluar fullscreen.'); return false; }
    if (e.ctrlKey || e.metaKey) {
      if (CTRL_COMBOS.includes(e.code)) { e.preventDefault(); e.stopPropagation(); showWarn('Shortcut dinonaktifkan.'); return false; }
      if (e.shiftKey && ['KeyI','KeyJ','KeyC'].includes(e.code)) { e.preventDefault(); e.stopPropagation(); showWarn('DevTools dinonaktifkan.'); return false; }
      if (e.code === 'KeyU') { e.preventDefault(); e.stopPropagation(); showWarn('View source dinonaktifkan.'); return false; }
    }
  }, {capture:true});

  function violation(reason){
    if (!window.EXAM.guardActive || window.EXAM.isSubmitting) return;
    // debounce
    if (window.EXAM.violationCooldown) return;
    window.EXAM.violationCooldown = true; setTimeout(()=>window.EXAM.violationCooldown=false, 800);

    window.EXAM.violationCount++;
    if (GUARD_POLICY.warnOnFirst && window.EXAM.violationCount === 1) showWarn(reason || 'Pelanggaran terdeteksi.');

    if (window.EXAM.violationCount >= GUARD_POLICY.maxViolations) {
      if (GUARD_POLICY.autoSubmitOnLimit) {
        window.EXAM.isAutoSubmit = true;
        window.EXAM.isSubmitting = true;
        cleanup().finally(()=> window.doSubmit(true)); // auto-submit tanpa confirm
        return;
      }
      window.EXAM.isSubmitting = true;
      cleanup().finally(() => window.location.href = "{{ route('Siswa.Course.index') }}");
    }
  }

  // Deteksi Alt+Tab / pindah tab / sembunyi halaman
  document.addEventListener('visibilitychange', ()=>{ if (window.EXAM.guardActive && document.hidden) violation('Berpindah dari tab/jendela.'); });
  window.addEventListener('blur', ()=>{ if (window.EXAM.guardActive) violation('Fokus keluar dari jendela ujian.'); });
  window.addEventListener('pagehide', ()=>{ if (window.EXAM.guardActive && !window.EXAM.isSubmitting) violation('Halaman disembunyikan / pindah konteks.'); });
  setInterval(()=>{ if (window.EXAM.guardActive && !window.EXAM.isSubmitting && !document.hasFocus()) violation('Jendela kehilangan fokus (Alt+Tab / pindah aplikasi).'); }, 800);
  document.addEventListener('fullscreenchange', ()=>{ if (window.EXAM.guardActive && GUARD_POLICY.requireFullscreen && !document.fullscreenElement){ showWarn('Tetap dalam mode layar penuh.'); violation('Keluar fullscreen.'); requestFullscreenLoop(); } });

  async function cleanup(){
    window.EXAM.guardActive = false;
    detachBeforeUnload();
    try{ document.exitFullscreen?.(); }catch{}
    try{ await window.EXAM.wakeLock?.release?.(); }catch{}
    try{ localStorage.removeItem(window.EXAM.LOCK_KEY); }catch{}
    if (window.EXAM.bc) window.EXAM.bc.postMessage({type:'leaving'});
  }

  async function startGuard(){
    if (!claimExamLock()) { alert('Terdeteksi lebih dari satu tab ujian. Menutup halaman.'); window.close(); return; }
    if (GUARD_POLICY.requireFullscreen) {
      const ok = await requestFullscreenLoop();
      if (!ok) alert('Izinkan fullscreen untuk memulai ujian.');
    }
    await requestWakeLock();
    attachBeforeUnload();
    window.EXAM.guardActive = true;
    showWarn('Mode Ujian aktif. Jangan berpindah tab/jendela.');
    // heartbeat lock
    setInterval(()=>{ try{ localStorage.setItem(window.EXAM.LOCK_KEY, JSON.stringify({by:STUDENT_ID, at:Date.now()})); }catch{} }, 5000);
  }
  startGuard();
})();

/* =========================
   INISIALISASI UI
========================= */
renderQuestion(window.EXAM.current);
renderNavigator();
updatePrevNext();
updateSubmitAvailability();
submitBtn.type = 'button';
submitBtn.addEventListener('click', ()=> window.doSubmit(false));
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
    window.doSubmit(false);
  }
});
startTimer();
</script>
@endpush
