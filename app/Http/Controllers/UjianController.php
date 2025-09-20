<?php

namespace App\Http\Controllers;

use App\Models\ujian;
use App\Models\kursus;
use App\Models\guru;
use App\Models\tipe_ujian;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UjianController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login'); // Redirect jika user tidak ditemukan
        }

        $guru = guru::where('id_user', $user->id)->first();

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        $id_kursus = $request->query('id_kursus');

        $courses = Kursus::with('guru')->get(); // Ambil semua kursus

        $course = $courses->where('id_kursus', $id_kursus)->first();

        if (!$course) {
            return redirect()->back()->withErrors(['error' => 'Kursus yang dipilih tidak valid.']);
        }

        $kursus = kursus::where('id_kursus', $id_kursus)
            ->where('id_guru', $guru->id_guru)
            ->first();

        if (!$kursus) {
            return redirect()->back()->withErrors(['error' => 'Kursus yang dipilih tidak valid.']);
        }

        $ujians = Ujian::where('id_kursus', $kursus->id_kursus)
            ->orderBy('tanggal_ujian', 'DESC')
            ->get();

        return view('Role.Guru.Course.index', compact('user', 'course', 'ujians', 'kursus', 'id_kursus', 'courses'));
    }

    public function create(Request $request)
    {
        $guru = guru::where('id_user', auth()->user()->id)->first();

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        $id_kursus = $request->query('id_kursus');

        $kursus = kursus::where('id_guru', $guru->id_guru)->get();

        $courses = kursus::with('guru')->get();

        $course = $courses->where('id_kursus', $id_kursus)->first();

        $tipeUjians = tipe_ujian::all();
        $user = auth()->user();

        return view('Role.Guru.Course.create', compact('kursus', 'courses', 'course', 'tipeUjians', 'user', 'id_kursus'));
    }

    public function store(Request $request)
    {
        Log::info('Received data for ujian creation:', $request->all());

        $validated = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'password_masuk' => 'required|string|min:6',
            'password_keluar' => 'required|string|min:6',
            'id_kursus' => 'required|exists:kursus,id_kursus', // Validasi id_kursus
            'id_tipe_ujian' => 'required|in:1,2,3',
            'acak' => 'nullable|in:Aktif,Tidak Aktif',
            'status_jawaban' => 'nullable|in:Aktif,Tidak Aktif',
            'grade' => 'nullable|numeric|min:0|max:100',
            'waktu_mulai' => 'required|date|after:now',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
        ]);
        $request->merge([
    'acak' => $request->input('acak', 'Aktif'), // Default 'Aktif' jika tidak diberikan
    'grade' => $request->input('grade', 100),   // Default 100 jika tidak diberikan
    'status_jawaban' => $request->input('status_jawaban', 'Aktif'), // Default 'Aktif' jika tidak diberikan
]);

        try {
            Log::info('Validated data:', $validated);

            $guru = guru::where('id_user', auth()->user()->id)->first();

            if (!$guru) {
                return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
            }

            $waktuMulai = Carbon::parse($validated['waktu_mulai']);
            $waktuSelesai = Carbon::parse($validated['waktu_selesai']);
            $durasi = $waktuMulai->diffInMinutes($waktuSelesai);

            $ujian = ujian::create([
                'id_guru' => $guru->id_guru,
                'nama_ujian' => $validated['nama_ujian'],
                'password_masuk' => Hash::make($validated['password_masuk']),  // Hash password masuk
                'password_keluar' => Hash::make($validated['password_keluar']),  // Hash password keluar
                'id_kursus' => $validated['id_kursus'],
                'id_tipe_ujian' => $validated['id_tipe_ujian'],
                'acak' => $validated['acak'] ?? 'Tidak Aktif',
                'status_jawaban' => $validated['status_jawaban'] ?? 'Tidak Aktif',
                'grade' => $validated['grade'] ?? 0,
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'durasi' => $durasi,
            ]);

            Log::info('Ujian successfully created:', ['ujian_id' => $ujian->id_ujian]);

            return redirect()->route('Guru.Ujian.index', ['id_kursus' => $validated['id_kursus']])->with('success', 'Ujian berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error storing ujian: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan ujian.']);
        }
    }

    public function show(ujian $ujian)
    {
        $course = $ujian->kursus;
        return view('Role.Guru.Course.index', compact('ujian', 'course'));
    }

    public function edit(Request $request, $id_ujian)
    {
        $ujian = ujian::where('id_ujian', $id_ujian)->firstOrFail();

        $id_kursus = $request->query('id_kursus');

        $user = auth()->user();

        $id_ujian = $request->query('id_ujian');

        $guru = guru::where('id_user', auth()->user()->id)->first();

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        $courses = kursus::where('id_guru', $guru->id_guru)->get();
        
        $courses = kursus::with('guru')->get();

        $course = $courses->where('id_kursus', $id_kursus)->first();

        if ($courses->isEmpty()) {
            return redirect()->back()->with('error', 'Kursus tidak ditemukan.');
        }

        return view('Role.Guru.Course.edit', compact('ujian','course','courses', 'guru', 'id_kursus', 'courses', 'user', 'id_ujian'));
    }

    public function update(Request $request, $id_ujian)
    {
        Log::info('Mulai validasi input.');

        $validated = $request->validate([
            'nama_ujian' => 'nullable|string|max:255',
            'id_tipe_ujian' => 'nullable|in:1,2,3',
            'acak' => 'nullable|in:Aktif,Tidak Aktif',
            'status_jawaban' => 'nullable|in:Aktif,Tidak Aktif',
            'grade' => 'nullable|numeric|min:0|max:100',
            'waktu_mulai' => 'nullable|date|after:now',
            'waktu_selesai' => 'nullable|date|after:waktu_mulai',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Log::info('Validasi input berhasil.');

        try {
            Log::info('Mencari ujian dengan id_ujian: ' . $id_ujian);
            $ujian = Ujian::findOrFail($id_ujian);

            Log::info('Memperbarui ujian dengan id_ujian: ' . $ujian->id_ujian);
            $ujian->nama_ujian = $validated['nama_ujian'] ?? $ujian->nama_ujian;
            $ujian->id_tipe_ujian = $validated['id_tipe_ujian'] ?? $ujian->id_tipe_ujian;
            $ujian->acak = $validated['acak'] ?? $ujian->acak;
            $ujian->status_jawaban = $validated['status_jawaban'] ?? $ujian->status_jawaban;
            $ujian->grade = $validated['grade'] ?? $ujian->grade;

            if (isset($validated['waktu_mulai'])) {
                $ujian->waktu_mulai = $validated['waktu_mulai'];
            }

            if (isset($validated['waktu_selesai'])) {
                $ujian->waktu_selesai = $validated['waktu_selesai'];
            }

            if ($request->hasFile('image')) {
                Log::info('File image ditemukan untuk ujian dengan id_ujian: ' . $ujian->id_ujian);

                if ($ujian->image) {
                    Storage::disk('public')->delete($ujian->image);
                    Log::info('Gambar lama berhasil dihapus.');
                }

                $ujian->image = $request->file('image')->store('images/ujians', 'public');
                Log::info('Gambar baru berhasil disimpan di: ' . $ujian->image);
            }

            $ujian->save();
            Log::info('Ujian berhasil diperbarui dengan id_ujian: ' . $ujian->id_ujian);

            $id_kursus = $request->input('id_kursus');

            if (!$id_kursus) {
                Log::error('id_kursus tidak ditemukan di request.');
                return redirect()->back()->withErrors(['error' => 'ID Kursus tidak ditemukan.']);
            }

            return redirect()->route('Guru.Ujian.index', ['id_kursus' => $id_kursus])->with('success', 'Ujian berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating Ujian with ID: ' . $id_ujian . '. Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui ujian.']);
        }
    }


    public function destroy(String $id_ujian)
    {
        try {
            $ujian = ujian::findOrFail($id_ujian);

            if ($ujian->Image) {
                Storage::disk('public')->delete($ujian->Image);
            }

            $ujian->delete();

            return redirect()->route('Guru.Ujian.index')->with('success', 'Ujian berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembali dengan pesan error
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus ujian.']);
        }
    }
    public function prosesNilai($id_ujian)
    {
        try {
            $ujian = ujian::findOrFail($id_ujian);
            $kursus = $ujian->kursus;  // Dapatkan kursus dari ujian yang sedang berlangsung
            $siswa = siswa::all();  // Ambil semua siswa yang mengikuti ujian

            $persentase = Persentase::where('id_kursus', $kursus->id_kursus)->first();

            if (!$persentase) {
                return redirect()->back()->withErrors(['error' => 'Persentase nilai untuk kursus ini tidak ditemukan.']);
            }

            foreach ($siswa as $s) {
                $nilai_ujian = $this->ambilNilaiUjian($ujian->id_ujian, $s->id_siswa);

                $nilaiTotal = ($nilai_ujian['nilai_kuis'] * $persentase->persentase_kuis / 100) +
                    ($nilai_ujian['nilai_ujian'] * $persentase->persentase_UTS / 100) +
                    ($nilai_ujian['nilai_uas'] * $persentase->persentase_UAS / 100);

                Nilai::create([
                    'id_kursus' => $kursus->id_kursus,
                    'id_siswa' => $s->id_siswa,
                    'nilai_kuis' => $nilai_ujian['nilai_kuis'],
                    'nilai_ujian' => $nilai_ujian['nilai_ujian'],
                    'nilai_uas' => $nilai_ujian['nilai_uas'],
                    'persentase_kuis' => $persentase->persentase_kuis,
                    'persentase_UTS' => $persentase->persentase_UTS,
                    'persentase_UAS' => $persentase->persentase_UAS,
                    'nilai_total' => $nilaiTotal,
                ]);
            }

            return redirect()->route('Guru.Ujian.index')->with('success', 'Nilai berhasil diproses.');
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Error processing nilai: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses nilai.']);
        }
    }

    private function ambilNilaiUjian($id_ujian, $id_siswa)
    {
        return [
            'nilai_kuis' => rand(0, 100),  // Nilai acak untuk kuis
            'nilai_ujian' => rand(0, 100),  // Nilai acak untuk ujian
            'nilai_uas' => rand(0, 100),    // Nilai acak untuk UAS
        ];
    }
}
