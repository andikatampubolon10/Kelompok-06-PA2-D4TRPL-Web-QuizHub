{{-- resources/views/Role/Guru/Nilai/bobot_tipe_soal_create.blade.php --}}
@extends('layouts.guru-layout')

@section('title', 'Tambah Bobot Tipe Soal')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Tambah Bobot Tipe Soal</h3>
    </div>

    <form action="{{ route('Guru.BobotTipeSoal.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="card-body">
            <input type="hidden" name="id_ujian" value="{{ $id_ujian ?? '' }}">

            <div class="form-group mb-3">
                <label for="id_tipe_soal" class="form-label">Tipe Soal</label>
                <select name="id_tipe_soal" id="id_tipe_soal"
                    class="form-control @error('id_tipe_soal') is-invalid @enderror" required>
                    <option value="">Pilih tipe soal</option>
                    @foreach($tipeSoal as $ts)
                        <option value="{{ $ts->id_tipe_soal }}">{{ $ts->nama_tipe_soal }}</option>
                    @endforeach
                </select>
                @error('id_tipe_soal')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="bobot" class="form-label">Bobot</label>
                <input
                    type="number"
                    step="0.01"
                    name="bobot"
                    id="bobot"
                    class="form-control @error('bobot') is-invalid @enderror"
                    value="{{ old('bobot') ?? '' }}"
                    required
                    min="0" max="100"
                    aria-describedby="bobotHelp"
                >
                @error('bobot')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
                <small id="bobotHelp" class="form-text text-muted">
                    Total bobot untuk ujian ini tidak boleh melebihi 100 setelah penjumlahan.
                </small>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('Guru.BobotTipeSoal.index', ['id_ujian' => $id_ujian]) }}" class="btn btn-default">
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('css')
<!-- Tambahkan CSS khusus jika diperlukan -->
@endpush

@push('js')
<script>
    // Validasi HTML5/BEM bisa ditambahkan di sini jika diperlukan
    // Contoh: memvalidasi total bobot lewat JavaScript sebelum submit (opsional)
</script>
@endpush