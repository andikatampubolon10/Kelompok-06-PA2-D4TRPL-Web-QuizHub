@extends('layouts.siswa')

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
      <h1 class="text-2xl font-bold mb-1">Course Name</h1>
      <p class="text-muted-foreground"></p>
    </div>
    <div class="flex items-center space-x-4">
      <div class="bg-card border border-border rounded-lg px-4 py-2">
        <span class="text-sm text-muted-foreground">Time Left</span>
        <div id="timer" class="text-2xl font-bold tracking-widest">--:--</div>
      </div>
      <button id="submitBtn" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:opacity-90">
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
          <div class="flex items-center space-x-2"><span class="w-3 h-3 inline-block bg-primary rounded-sm"></span><span>Current</span></div>
          <div class="flex items-center space-x-2"><span class="w-3 h-3 inline-block bg-green-600 rounded-sm"></span><span>Answered</span></div>
          <div class="flex items-center space-x-2"><span class="w-3 h-3 inline-block bg-border rounded-sm"></span><span>Unanswered</span></div>
        </div>
      </div>
    </div>
  </div>

  {{-- Data ujian dikirim ke JS --}}

@endsection

@push('scripts')
<script>
  // STATE
  let current = 0; // index soal (0-based)
  const answers = Array(TOTAL).fill(null);
  let remaining = DURATION; // seconds
  let timerId = null;

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

  function renderQuestion(idx){
    const q = QUESTIONS[idx];
    qIndexEl.textContent = idx+1;
    qTitleEl.textContent = q.text;

    // Render pilihan
    choicesEl.innerHTML = '';
    q.choices.forEach((c, i) => {
      const id = `q${q.id}_c${i}`;
      const wrap = document.createElement('label');
      wrap.className = 'flex items-center space-x-3 p-3 border border-border rounded-lg hover:bg-secondary cursor-pointer';

      const input = document.createElement('input');
      input.type = 'radio';
      input.name = `q_${q.id}`;
      input.value = c;
      input.className = 'form-radio';
      input.checked = (answers[idx] === c);
      input.addEventListener('change', () => {
        answers[idx] = c;
        renderNavigator();
      });

      const text = document.createElement('span');
      text.textContent = c;

      wrap.appendChild(input);
      wrap.appendChild(text);
      choicesEl.appendChild(wrap);
    });
  }

  function renderNavigator(){
    navGridEl.innerHTML = '';
    for(let i=0;i<TOTAL;i++){
      const btn = document.createElement('button');
      btn.className = 'w-10 h-10 rounded-md border text-sm';
      btn.textContent = i+1;

      const isCurrent = (i === current);
      const isAnswered = answers[i] !== null;

      if(isCurrent){
        btn.classList.add('bg-primary','text-primary-foreground','border-primary');
      }else if(isAnswered){
        btn.classList.add('bg-green-600','text-white','border-green-600');
      }else{
        btn.classList.add('border-border','text-foreground','bg-transparent','hover:bg-secondary');
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

  function updatePrevNext(){
    prevBtn.disabled = (current === 0);
    nextBtn.textContent = (current === TOTAL-1) ? 'Finish' : 'Next';
  }

  prevBtn.addEventListener('click', () => {
    if(current > 0){
      current--;
      renderQuestion(current);
      renderNavigator();
      updatePrevNext();
    }
  });

  nextBtn.addEventListener('click', () => {
    if(current < TOTAL-1){
      current++;
      renderQuestion(current);
      renderNavigator();
      updatePrevNext();
    } else {
      // Finish alias tekan submit
      doSubmit();
    }
  });

  submitBtn.addEventListener('click', doSubmit);

  function startTimer(){
    updateTimerUI();
    timerId = setInterval(() => {
      remaining--;
      if(remaining <= 0){
        remaining = 0;
        clearInterval(timerId);
        alert('Waktu habis! Jawaban akan dikumpulkan.');
        doSubmit();
      }
      updateTimerUI();
    }, 1000);
  }

  function updateTimerUI(){
    const m = Math.floor(remaining/60).toString().padStart(2,'0');
    const s = (remaining%60).toString().padStart(2,'0');
    timerEl.textContent = `${m}:${s}`;
  }

  
</script>
@endpush
