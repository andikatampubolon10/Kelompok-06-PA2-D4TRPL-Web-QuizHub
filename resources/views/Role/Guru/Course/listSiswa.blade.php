@extends('layouts.guru-layout')

@section('title', 'Guru | Kursus | List Siswa')

@section('content')
    <div class="w-full bg-white p-6 shadow-md rounded-lg">
        {{-- Breadcrumb --}}
        <nav class="text-gray-600 text-lg mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li>
                    <a href="{{ route('Guru.Course.index') }}" class="text-blue-600 hover:underline">Course</a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li>
                    <span class="text-blue-600">List Siswa</span>
                </li>
            </ol>
        </nav>

        {{-- Judul Kursus --}}
        <div class="border-b border-gray-300 pb-1 mb-4">
            <h1 class="text-3xl leading-none font-semibold text-teal-700">
                {{ $kursus->nama_kursus }}
            </h1>
        </div>

        {{-- Persentase + Aksi --}}
        <form class="mb-8 space-y-6">
            @foreach ($persentase as $percent)
                @if ($percent->tipePersentase->nama_persentase == 'persentase_kuis')
                    <div class="flex justify-between items-center text-base font-normal mb-2">
                        <div>Persentase Kuis: {{ $percent->persentase }} %</div>
                    </div>
                @elseif ($percent->tipePersentase->nama_persentase == 'persentase_UTS')
                    <div class="flex justify-between items-center text-base font-normal mb-2">
                        <div>Persentase Ujian Tengah Semester: {{ $percent->persentase }} %</div>
                    </div>
                @elseif ($percent->tipePersentase->nama_persentase == 'persentase_UAS')
                    <div class="flex justify-between items-center text-base font-normal mb-4">
                        <div>Persentase Ujian Akhir Semester: {{ $percent->persentase }} %</div>
                    </div>
                @endif
            @endforeach

            <div class="flex justify-between">
                <button id="resetRecalculateBtn" data-id-kursus="{{ $kursus->id_kursus }}" type="button"
                    class="px-4 py-2 text-base font-normal border border-green-700 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-300">
                    <i class="fas fa-calculator mr-1"></i> Hitung Nilai
                </button>

                <a href="{{ route('Guru.nilai.export', ['id_kursus' => $kursus->id_kursus]) }}"
                    class="px-4 py-2 text-base font-normal border border-blue-700 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-300 ml-auto">
                    Export Nilai
                </a>
            </div>
        </form>

        {{-- List Siswa --}}
        <section class="border-t border-gray-300 pt-4 mb-4">
            <h2 class="text-2xl mb-4 font-semibold">List Siswa</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse bg-white rounded-lg shadow">
                    <thead>
                        <tr class="bg-teal-600 text-white">
                            <th class="px-3 py-2 text-center">NIS</th>
                            <th class="px-3 py-2 text-center">Nama Siswa</th>
                            <th class="px-3 py-2 text-center">Kelas</th>
                            <th class="px-3 py-2 text-center">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswa as $student)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-3 py-2 text-center">{{ $student->nis }}</td>
                                <td class="px-3 py-2 text-center">{{ $student->nama_siswa }}</td>
                                <td class="px-3 py-2 text-center">
                                    {{ $student->kelas ? $student->kelas->nama_kelas : 'Kelas tidak tersedia' }}
                                </td>
                                <td class="px-3 py-2 text-center" id="nilai-{{ $student->id_siswa }}">
                                    {{ isset($nilai[$student->id_siswa]) ? number_format($nilai[$student->id_siswa]->nilai_total, 2) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-5 rounded-lg flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500 mb-3"></div>
            <p class="text-gray-700">Menghitung nilai...</p>
        </div>
    </div>

    <style>
        /* highlight animasi untuk sel nilai yang berubah */
        @keyframes highlight {
            0% { background-color: #ffffff; }
            50% { background-color: #c8e6c9; }
            100% { background-color: #ffffff; }
        }
        .highlight { animation: highlight 2s ease; }
    </style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetRecalculateBtn = document.getElementById('resetRecalculateBtn');
    const loadingOverlay      = document.getElementById('loadingOverlay');
    const csrf                = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    if (!resetRecalculateBtn) return;

    resetRecalculateBtn.addEventListener('click', function() {
        const idKursus = this.getAttribute('data-id-kursus');

        if (!idKursus) {
            alert('ID kursus tidak ditemukan.');
            return;
        }
        if (!confirm('Apakah Anda yakin ingin reset dan menghitung ulang semua nilai?')) {
            return;
        }

        // tampilkan loading
        loadingOverlay.classList.remove('hidden');
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghitung...';

        fetch(`{{ url('/Guru/reset-recalculate-nilai') }}/${idKursus}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(async (res) => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(text || `HTTP ${res.status}`);
            }
            return res.json();
        })
        .then((data) => {
            loadingOverlay.classList.add('hidden');
            resetRecalculateBtn.disabled = false;
            resetRecalculateBtn.innerHTML = '<i class="fas fa-calculator mr-1"></i> Hitung Nilai';

            if (!data || data.success !== true) {
                const msg = data?.message || 'Terjadi kesalahan tidak diketahui.';
                alert('Terjadi kesalahan: ' + msg);
                return;
            }

            const hasil  = data.data?.hasil || {};
            const jumlah = data.data?.jumlah_siswa ?? Object.keys(hasil).length;

            for (const siswaId in hasil) {
                const cell = document.getElementById(`nilai-${siswaId}`);
                if (!cell) continue;

                const n = parseFloat(hasil[siswaId]?.nilai_total ?? 0);
                cell.textContent = isFinite(n) ? n.toFixed(2) : '-';
                cell.classList.add('highlight');
                setTimeout(() => cell.classList.remove('highlight'), 2000);
            }

            alert(`Perhitungan nilai berhasil dilakukan untuk ${jumlah} siswa!`);
        })
        .catch((error) => {
            loadingOverlay.classList.add('hidden');
            resetRecalculateBtn.disabled = false;
            resetRecalculateBtn.innerHTML = '<i class="fas fa-calculator mr-1"></i> Hitung Nilai';

            try {
                const j = JSON.parse(error.message);
                alert('Terjadi kesalahan: ' + (j.message || error.message));
            } catch (_) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    });
});
</script>
@endpush
