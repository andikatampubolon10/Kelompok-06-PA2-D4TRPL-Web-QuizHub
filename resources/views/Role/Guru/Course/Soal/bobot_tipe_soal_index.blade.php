@extends('layouts.guru-layout')

@section('title', 'Bobot Tiap Tipe Soal')

@section('content')
    <div class="space-y-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    Bobot Tiap Tipe Soal untuk Ujian
                    @if ($ujian)
                        : {{ $ujian->nama_ujian ?? 'ID ' . ($ujian->id_ujian ?? '') }}
                    @endif
                </h3>
                <div class="card-tools">
                    @if ($id_ujian)
                        <a href="{{ route('Guru.BobotTipeSoal.create', ['id_ujian' => $id_ujian]) }}"
                            class="btn btn-sm btn-primary">
                            Tambah Bobot
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if ($bobotTipeSoals->count() || $ujian)
                    <div class="table-responsive">
                        <table id="dataTable1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipe Soal</th>
                                    <th>Ujian</th>
                                    <th>Bobot</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bobotTipeSoals as $bt)
                                    <tr>
                                        <td>{{ $bt->id_bobot_tipe_soal }}</td>
                                        <td>{{ $bt->tipe_soal->nama_tipe_soal ?? 'N/A' }}</td>
                                        <td>{{ $bt->ujian->nama_ujian ?? 'N/A' }}</td>
                                        <td>{{ $bt->bobot }}</td>
                                        <td class="d-flex align-items-center">
                                            <a href="{{ route('Guru.BobotTipeSoal.edit', ['id_bobot_tipe_soal' => $bt->id_bobot_tipe_soal]) }}"
                                                class="btn btn-sm btn-warning mr-2">Edit</a>

                                            <form
                                                action="{{ route('Guru.BobotTipeSoal.destroy', ['id_bobot_tipe_soal' => $bt->id_bobot_tipe_soal]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger delete-button">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        Tidak ada bobot tipe soal untuk ujian ini.
                        @if ($id_ujian)
                            <span> <a href="{{ route('Guru.BobotTipeSoal.create', ['id_ujian' => $id_ujian]) }}">Tambahkan
                                    sekarang</a>.</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('css')
    <!-- Tambahkan CSS jika diperlukan -->
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables jika plugin tersedia
            if ($.fn.DataTable) {
                $('#dataTable1').DataTable();
            }
        });
    </script>
@endpush
