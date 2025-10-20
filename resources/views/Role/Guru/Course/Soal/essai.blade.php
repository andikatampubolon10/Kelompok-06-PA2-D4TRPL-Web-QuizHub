@extends('layouts.guru-layout')

@section('title', 'Guru | Soal | Essai')

@section('content')
    <div class="w-full bg-white p-6 rounded-lg shadow-md h-full">
        <form action="{{ route('Guru.Soal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tipe Soal --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="question_type">Tipe Soal</label>
                <select id="question_type" name="id_tipe_soal"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="3" selected>Essay</option>
                </select>
            </div>

            {{-- Soal --}}
            <label class="block text-gray-700 text-sm font-bold mb-2">Soal</label>
            <div class="border p-2">
                
                 <input type="hidden" name="id_ujian" value="{{ request()->query('id_ujian') }}">

                <textarea id="soal-textarea" name="soal" class="w-full border p-2" rows="4">{{ old('soal') }}</textarea>

                <div id="image-preview-soal" class="mt-2"></div>
                <input type="file" id="image-input-soal" name="image" class="hidden" accept="image/*" />

                @error('soal')
                    <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
                @enderror
            </div>

            {{-- Jawaban Benar --}}
            <div class="mb-4 mt-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="correct_answer">Jawaban Benar</label>
                <textarea id="correct_answer" name="correct_answer" class="w-full border p-2" rows="2">{{ old('correct_answer') }}</textarea>
                @error('correct_answer')
                    <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="bobot" class="block text-gray-700 text-sm font-bold mb-2">
                    Bobot Soal <span class="text-gray-400 text-xs">(diantara 1 - 10)</span>
                </label>
                <input type="number" name="bobot" id="bobot" step="0.01" min="0"
                    value="{{ old('bobot') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    {{-- kalau ada id_ujian di URL, wajib diisi (sekadar hint di sisi client) --}} @if (request()->filled('id_ujian')) required @endif>
                @error('bobot')
                    <span class="alert-danger">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    Catatan: untuk ujian, bobot biasanya wajib; untuk latihan bisa opsional.
                </p>
            </div>

            {{-- Latihan (Opsional) --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="id_latihan">Latihan (Untuk Pembuatan Soal
                    Latihan)</label>
                <select name="id_latihan" id="id_latihan"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Pilih Latihan (Opsional)</option>
                    @foreach ($latihan as $latihans)
                        <option value="{{ $latihans->id_latihan }}"
                            {{ old('id_latihan') == $latihans->id_latihan ? 'selected' : '' }}>
                            {{ $latihans->Topik }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Aksi --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <span>Simpan</span>
                    <i class="fas fa-check ml-2"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- kecilin helper error default --}}
    <style>
        .alert-danger {
            color: #e74c3c;
            font-size: 14px;
            font-weight: 600;
            margin-top: 5px;
        }
    </style>

    
@endsection

@push('scripts')
    <script>
        /* === Handler Dropdown di layout (opsional) ===
           Kalau layout kamu sudah punya handler dropdown, bagian ini boleh di-skip.
        */
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdown-menu");
            if (dropdown) dropdown.classList.toggle("show");
        }
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById("dropdown-menu");
            if (dropdown && !e.target.closest('.dropdown') && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });

        /* === Ganti tipe soal === */
        const questionType = document.getElementById('question_type');
        if (questionType) {
            questionType.addEventListener('change', function() {
                const selectedValue = this.value;
                if (selectedValue) {
                    // arahkan ke halaman create sesuai tipe
                    window.location.href = '/Guru/Soal/create/' + selectedValue;
                }
            });
        }

        /* === Toolbar Soal === */
        const soalTextarea = document.getElementById('soal-textarea');
        const listBtnSoal = document.getElementById('list-button-soal');
        const boldBtnSoal = document.getElementById('bold-button-soal');
        const imgBtnSoal = document.getElementById('image-button-soal');
        const imgInputSoal = document.getElementById('image-input-soal');
        const imgPrevSoal = document.getElementById('image-preview-soal');

        if (listBtnSoal && soalTextarea) {
            listBtnSoal.addEventListener('click', function(e) {
                e.preventDefault();
                soalTextarea.value += (soalTextarea.value.endsWith('\n') ? '' : '\n') + '- ';
                soalTextarea.focus();
            });
        }

        if (boldBtnSoal && soalTextarea) {
            boldBtnSoal.addEventListener('click', function(e) {
                e.preventDefault();
                const start = soalTextarea.selectionStart;
                const end = soalTextarea.selectionEnd;
                const selectedText = soalTextarea.value.substring(start, end) || 'teks';
                const newText = `<strong>${selectedText}</strong>`;
                soalTextarea.setRangeText(newText, start, end, 'end');
                soalTextarea.focus();
            });
        }

        if (imgBtnSoal && imgInputSoal) {
            imgBtnSoal.addEventListener('click', function(e) {
                e.preventDefault();
                imgInputSoal.click();
            });
        }
        if (imgInputSoal && imgPrevSoal) {
            imgInputSoal.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPrevSoal.innerHTML = `
                <div class="relative mt-2 inline-block">
                    <img src="${e.target.result}" alt="Preview" class="max-w-full h-auto max-h-40 border rounded">
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer"
                          onclick="(function(){ document.getElementById('image-preview-soal').innerHTML=''; document.getElementById('image-input-soal').value=''; })()">×</span>
                </div>`;
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endpush
