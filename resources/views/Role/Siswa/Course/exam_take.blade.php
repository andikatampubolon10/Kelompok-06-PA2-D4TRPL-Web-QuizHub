@extends('layouts.ujian')

@section('title')

@section('breadcrumb')
  <li><a href="" class="hover:text-foreground">My Courses</a></li>
  <li><span class="mx-2">/</span></li>
  <li><a href="" class="hover:text-foreground">Exams</a></li>
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

        <div id="choices" class="space-y-3">
          {{-- Pilihan jawaban akan dirender oleh JS dari array questions --}}
        </div>

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
        <div id="navGrid" class="grid grid-cols-5 gap-2">
          {{-- Button nomor soal dirender JS --}}
        </div>
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

  {{-- Data ujian dikirim ke JS --}}
<form id="submitForm" method="POST"
      action="{{ route('Siswa.Ujian.submit', [$kursus->id_kursus, $ujian->id_ujian]) }}">
  @csrf
  <input type="hidden" id="answers_json" name="answers_json">
</form>

@endsection

{{-- Data ujian dikirim ke JS --}}
<script>
  const QUESTIONS = @json($questions);   // [{id,text,tipe_id/type,choices?}, ...]
  const TOTAL     = {{ $total }};
  const DURATION  = {{ $duration }};     // in seconds
  let timerId = null;
</script>

@push('scripts')
<script>
  const TYPE = { PG: 1, TF: 2, ISIAN: 3 };

  // Util: read type from the question object
  function getQType(q) {
    if (q.tipe_id) {
      if (q.tipe_id === 1) return 'pg'; // Multiple Choice
      if (q.tipe_id === 2) return 'tf'; // True/False
      if (q.tipe_id === 3) return 'isian'; // Essay (Fill in the blank)
    }
    return 'pg';
  }

  // STATE
  let current = 0; // question index (0-based)
  const answers = Array(TOTAL).fill(null);
  let remaining = DURATION; // seconds

  // DOM refs
  const qIndexEl = document.getElementById('qIndex');
  const qTitleEl = document.getElementById('qTitle');
  const choicesEl = document.getElementById('choices');
  const navGridEl = document.getElementById('navGrid');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');
  const timerEl = document.getElementById('timer');

  // Init
  renderQuestion(current);
  renderNavigator();
  updatePrevNext();
  startTimer();

  function renderQuestion(idx) {
    const q = QUESTIONS[idx];
    const qType = getQType(q); // Determine question type
    qIndexEl.textContent = idx + 1;
    qTitleEl.textContent = q.text;

    choicesEl.innerHTML = '';  // Reset choices

    if (qType === 'pg') {
      const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
      const list = q.choices || [];
      list.forEach((choiceText, i) => {
        const optKey = letters[i] || String.fromCharCode(65 + i);  // A, B, C, ...
        const wrap = document.createElement('label');
        wrap.className = 'flex items-center gap-3 p-3 border border-border rounded-lg hover:bg-secondary cursor-pointer';

        const input = document.createElement('input');
        input.type = 'radio';
        input.name = `q_${q.id}`;
        input.value = optKey; // Key for choices (A, B, C, D)
        input.className = 'form-radio';
        input.checked = (answers[idx] === optKey);
        input.addEventListener('change', () => {
          answers[idx] = optKey;
          renderNavigator();
        });

        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center justify-center w-8 h-8 rounded-md border border-border text-sm font-semibold';
        badge.textContent = optKey;

        const text = document.createElement('span');
        text.textContent = choiceText;

        wrap.appendChild(input);
        wrap.appendChild(badge);
        wrap.appendChild(text);
        choicesEl.appendChild(wrap);
      });

    } else if (qType === 'tf') {
      const tfChoices = q.choices && q.choices.length ? q.choices : ['True', 'False'];
      const mapKey = (txt) => (/^t(rue)?|^b(enar)?|^ya/i.test(txt) ? 'T' : 'F');

      tfChoices.slice(0, 2).forEach((txt) => {
        const key = mapKey(String(txt).trim());
        const wrap = document.createElement('label');
        wrap.className = 'flex items-center gap-3 p-3 border border-border rounded-lg hover:bg-secondary cursor-pointer';

        const input = document.createElement('input');
        input.type = 'radio';
        input.name = `q_${q.id}`;
        input.value = key;
        input.className = 'form-radio';
        input.checked = (answers[idx] === key);
        input.addEventListener('change', () => {
          answers[idx] = key;
          renderNavigator();
        });

        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center justify-center w-10 h-8 rounded-md border border-border text-sm font-semibold';
        badge.textContent = (key === 'T') ? 'T' : 'F';

        const text = document.createElement('span');
        text.textContent = (key === 'T') ? 'True' : 'False';

        wrap.appendChild(input);
        wrap.appendChild(badge);
        wrap.appendChild(text);
        choicesEl.appendChild(wrap);
      });

    } else if (qType === 'isian') {
      const wrap = document.createElement('div');
      wrap.className = 'space-y-2';

      const input = document.createElement('textarea');
      input.name = `q_${q.id}`;
      input.rows = 4;  // Can increase to accommodate longer answers
      input.placeholder = 'Type your answer here…';
      input.className = 'w-full rounded border border-border bg-white text-gray-900 placeholder-gray-500 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500';
      input.value = (answers[idx] ?? '');
      input.addEventListener('input', () => {
        answers[idx] = input.value.trim();
        renderNavigator();
      });

      wrap.appendChild(input);
      choicesEl.appendChild(wrap);
    }
  }

  function renderNavigator() {
    navGridEl.innerHTML = '';
    for (let i = 0; i < TOTAL; i++) {
      const btn = document.createElement('button');
      btn.className = 'w-10 h-10 rounded-md border text-sm';
      btn.textContent = i + 1;

      const isCurrent = (i === current);
      const ans = answers[i];
      const isAnswered = (ans !== null && String(ans).trim() !== ''); // Supports essay type

      if (isCurrent) {
        btn.classList.add('bg-primary', 'text-primary-foreground', 'border-primary');
      } else if (isAnswered) {
        btn.classList.add('bg-green-600', 'text-white', 'border-green-600');
      } else {
        btn.classList.add('border-border', 'text-foreground', 'bg-transparent', 'hover:bg-secondary');
      }

      btn.addEventListener('click', () => {
        current = i;
        renderQuestion(current);
        renderNavigator();
        updatePrevNext();
      });

      navGridEl.appendChild(btn);
    }
  }

  function updatePrevNext() {
    prevBtn.disabled = (current === 0);
    nextBtn.textContent = (current === TOTAL - 1) ? 'Finish' : 'Next';
  }

  prevBtn.addEventListener('click', () => {
    if (current > 0) {
      current--;
      renderQuestion(current);
      renderNavigator();
      updatePrevNext();
    }
  });

  nextBtn.addEventListener('click', () => {
    if (current < TOTAL - 1) {
      current++;
      renderQuestion(current);
      renderNavigator();
      updatePrevNext();
    } else {
      doSubmit();
    }
  });

  submitBtn.addEventListener('click', doSubmit);

  function startTimer() {
    updateTimerUI();
    timerId = setInterval(() => {
      remaining--;
      if (remaining <= 0) {
        remaining = 0;
        clearInterval(timerId);
        alert('Time is up! Your answers will be submitted.');
        doSubmit();
      }
      updateTimerUI();
    }, 1000);
  }

  function updateTimerUI() {
    const m = Math.floor(remaining / 60).toString().padStart(2, '0');
    const s = (remaining % 60).toString().padStart(2, '0');
    timerEl.textContent = `${m}:${s}`;
  }

  function doSubmit() {
    if (!confirm('Are you sure you want to submit the exam?')) {
      return;
    }

    const payload = QUESTIONS.map((q, i) => {
      const type = getQType(q);
      const ans = answers[i];
      let choiceId = null;

      if ((type === 'pg' || type === 'tf') && q.choice_ids) {
        choiceId = q.choice_ids[String(ans)] ?? null;
      }

      return {
        id_soal: q.id,
        jawaban_siswa: (ans == null ? '' : String(ans)),
        id_jawaban_soal: choiceId
      };
    });

    document.getElementById('answers_json').value = JSON.stringify(payload);
    document.getElementById('submitForm').submit();
  }
</script>
@endpush
