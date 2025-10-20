@extends('layouts.guru-layout')

@section('title', 'Guru | Soal | Essai | Edit')

@section('content')
    <div class="w-full bg-white p-6 rounded-lg shadow-md h-full">
        <h2 class="text-2xl font-bold mb-6">Edit Soal Essay</h2>

        <form action="{{ route('Guru.Soal.update', $soal->id_soal) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_tipe_soal" value="{{ $soal->id_tipe_soal }}">

            {{-- Soal --}}
            <label class="block text-gray-700 text-sm font-bold mb-2">Soal</label>
            <div class="border p-2">
                
                <textarea id="soal-textarea" name="soal" class="w-full border p-2" rows="4">{{ $soal->soal }}</textarea>

                
                <input type="file" id="image-input-soal" name="image" class="hidden" accept="image/*" />

                @error('soal')
                    <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
                @enderror
            </div>

            {{-- Ambil jawaban soal --}}
            @php
                $jawaban = $soal->jawaban_soal->sortBy('id_jawaban_soal')->values();
            @endphp

            {{-- Jawaban Essay --}}
            <div class="border p-2 mb-4 mt-4">
                <div class="flex space-x-2 mb-2">
                    <button type="button" class="border p-1" id="list-button-1" title="Bullet List">
                        <i class="fas fa-list"></i>
                    </button>
                    <button type="button" class="border p-1" id="bold-button-1" title="Bold">
                        <i class="fas fa-bold"></i>
                    </button>
                </div>
                <textarea id="jawaban-1-textarea" name="jawaban_1" placeholder="Jawaban Essay" class="w-full border p-2" rows="4">{{ $jawaban[0]->jawaban ?? '' }}</textarea>
                @error('jawaban_1')
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
            
            {{-- Correct Answer (essay selalu jawaban_1) --}}
            <input type="hidden" name="correct_answer" value="jawaban_1">

            {{-- Latihan (Opsional) --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="id_latihan">Latihan</label>
                <select name="id_latihan" id="id_latihan"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Pilih Latihan (Opsional)</option>
                    @foreach ($latihan as $latihans)
                        <option value="{{ $latihans->id_latihan }}"
                            {{ $soal->id_latihan == $latihans->id_latihan ? 'selected' : '' }}>
                            {{ $latihans->Topik }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg flex items-center">
                    <span>Simpan</span>
                    <i class="fas fa-check ml-2"></i>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // === Toolbar Soal ===
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

        // === Toolbar Jawaban Essay ===
        const ansTextarea = document.getElementById('jawaban-1-textarea');
        const listBtn1 = document.getElementById('list-button-1');
        const boldBtn1 = document.getElementById('bold-button-1');

        if (listBtn1 && ansTextarea) {
            listBtn1.addEventListener('click', function(e) {
                e.preventDefault();
                ansTextarea.value += (ansTextarea.value.endsWith('\n') ? '' : '\n') + '- ';
                ansTextarea.focus();
            });
        }
        if (boldBtn1 && ansTextarea) {
            boldBtn1.addEventListener('click', function(e) {
                e.preventDefault();
                const start = ansTextarea.selectionStart;
                const end = ansTextarea.selectionEnd;
                const selectedText = ansTextarea.value.substring(start, end) || 'teks';
                const newText = `<strong>${selectedText}</strong>`;
                ansTextarea.setRangeText(newText, start, end, 'end');
                ansTextarea.focus();
            });
        }

        // Remove image helper (dipanggil dari inline onclick)
        function removeImage(section) {
            const preview = document.getElementById(`image-preview-${section}`);
            const input = document.getElementById(`image-input-${section}`);
            if (preview) preview.innerHTML = '';
            if (input) input.value = '';
        }
    </script>
     
@endpush
