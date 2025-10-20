<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Ujian;
use App\Models\Guru;
use App\Models\Latihan;
use App\Models\TipeSoal;
use App\Models\JawabanSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SoalController extends Controller
{
    // PG & BS tipe IDs (ikuti mapping kamu: 1=PG, 2=BS, 3=Essay)
    private const TIPE_PG_BS = [1, 2];

    public function index(Request $request)
    {
        $idUjian   = $request->get('id_ujian');
        $idLatihan = $request->get('id_latihan');

        $soals = null;
        if ($idUjian) {
            $soals = Soal::where('id_ujian', $idUjian)
                ->with(['ujian', 'latihan', 'tipe_soal'])
                ->orderBy('id_soal', 'DESC')
                ->get();
        } elseif ($idLatihan) {
            $soals = Soal::where('id_latihan', $idLatihan)
                ->with(['latihan', 'tipe_soal'])
                ->orderBy('id_soal', 'DESC')
                ->get();
        }

        $user = auth()->user();
        $guru = \App\Models\Guru::where('id_user', $user->id)->first();

        if (!$guru) {
            return redirect()->back()->withErrors(['error' => 'Guru tidak ditemukan.']);
        }

        return view('Role.Guru.Course.Soal.index', compact('soals', 'user', 'idUjian', 'idLatihan'));
    }

    public function create($type, Request $request)
    {
        $users     = auth()->user();
        $latihan   = Latihan::all();
        $id_ujian  = $request->query('id_ujian');
        $id_kursus = $request->query('id_kursus');

        $courses = Kursus::with('guru')->get();
        $course  = $courses->where('id_kursus', $id_kursus)->first();

        switch ($type) {
            case 'pilgan':
                return view('Role.Guru.Course.Soal.pilber', compact('users','latihan','id_kursus','courses','course','id_ujian'));
            case 'truefalse':
                return view('Role.Guru.Course.Soal.truefalse', compact('users','latihan','id_kursus','courses','course','id_ujian'));
            case 'essay':
                return view('Role.Guru.Course.Soal.essai', compact('users','latihan','id_kursus','courses','course','id_ujian'));
            default:
                return redirect()->route('Guru.Soal.index')->with('error', 'Tipe soal tidak valid.');
        }
    }

    public function store(Request $request)
    {
        Log::info('Menerima request untuk membuat soal.');

        $validated = $request->validate([
            'soal'          => 'required|string',
            'id_tipe_soal'  => 'required|exists:tipe_soal,id_tipe_soal',
            'id_latihan'    => 'nullable|exists:latihan,id_latihan',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'jawaban_1'     => 'nullable|string|max:30',
            'jawaban_2'     => 'nullable|string|max:30',
            'jawaban_3'     => 'nullable|string|max:30',
            'jawaban_4'     => 'nullable|string|max:30',
            'jawaban_5'     => 'nullable|string|max:30',
            'correct_answer'=> 'required|string',
            'bobot'         => 'nullable|numeric|min:0',
        ]);

        $idUjian   = $request->input('id_ujian');
        $idLatihan = $validated['id_latihan'] ?? null;

        if (!$idUjian && !$idLatihan) {
            $guru   = \App\Models\Guru::where('id_user', auth()->user()->id)->first();
            $kursus = $guru?->kursus()->first();
            $ujian  = $kursus?->ujian()->first();
            $idUjian = $ujian?->id_ujian;
        }

        if (!$idUjian && !$idLatihan) {
            return redirect()->route('Guru.Soal.index')->with('error', 'Ujian atau Latihan tidak ditemukan.');
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // handle image
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = public_path('images');
            if (!is_dir($imagePath)) mkdir($imagePath, 0755, true);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imagePath, $imageName);
            $imageUrl  = url('images/' . $imageName);
        } else {
            $imageName = null;
            $imageUrl  = null;
        }

        DB::beginTransaction();
        try {
            if ($idLatihan) {
                $soal = Soal::create([
                    'soal'         => $validated['soal'],
                    'image'        => $imageName,
                    'image_url'    => $imageUrl,
                    'id_latihan'   => $idLatihan,
                    'id_tipe_soal' => $validated['id_tipe_soal'],
                    // bobot akan diatur otomatis jika PG/BS
                    'bobot'        => $validated['bobot'] ?? null,
                ]);
                Log::info('Soal latihan dibuat', ['id' => $soal->id_soal]);
            } else {
                $soal = Soal::create([
                    'soal'         => $validated['soal'],
                    'image'        => $imageName,
                    'image_url'    => $imageUrl,
                    'id_ujian'     => $idUjian,
                    'id_tipe_soal' => $validated['id_tipe_soal'],
                    // bobot akan diatur otomatis jika PG/BS
                    'bobot'        => $validated['bobot'] ?? null,
                    'id_latihan'   => null,
                ]);
                Log::info('Soal ujian dibuat', ['id' => $soal->id_soal]);
            }

            // Jawaban
            $jawaban_data = [];
            if ((int)$validated['id_tipe_soal'] === 1) {
                $jawaban_data = [
                    ['jawaban'=>$validated['jawaban_1'],'benar'=>$validated['correct_answer']==='jawaban_1','id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_2'],'benar'=>$validated['correct_answer']==='jawaban_2','id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_3'],'benar'=>$validated['correct_answer']==='jawaban_3','id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_4'],'benar'=>$validated['correct_answer']==='jawaban_4','id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_5'],'benar'=>$validated['correct_answer']==='jawaban_5','id_tipe_soal'=>1],
                ];
            } elseif ((int)$validated['id_tipe_soal'] === 2) {
                $jawaban_data = [
                    ['jawaban'=>$validated['jawaban_1'],'benar'=>$validated['correct_answer']==='jawaban_1','id_tipe_soal'=>2],
                    ['jawaban'=>$validated['jawaban_2'],'benar'=>$validated['correct_answer']==='jawaban_2','id_tipe_soal'=>2],
                ];
            } elseif ((int)$validated['id_tipe_soal'] === 3) {
                $jawaban_data = [
                    ['jawaban'=>$validated['correct_answer'],'benar'=>true,'id_tipe_soal'=>3],
                ];
            }
            $soal->jawaban_soal()->createMany($jawaban_data);

            // === Re-balance bobot otomatis untuk PG & BS ===
            if (in_array((int)$soal->id_tipe_soal, self::TIPE_PG_BS, true)) {
                if ($soal->id_ujian) {
                    $this->rebalanceBobotUjian((int)$soal->id_ujian);
                } elseif ($soal->id_latihan) {
                    $this->rebalanceBobotLatihan((int)$soal->id_latihan);
                }
            }

            DB::commit();

            if ($idUjian) {
                return redirect()->route('Guru.Soal.index', ['id_ujian' => $idUjian])->with('success', 'Soal berhasil dibuat & bobot diperbarui.');
            }
            return redirect()->route('Guru.Latihan.index')->with('success', 'Soal latihan berhasil dibuat & bobot diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal membuat soal', ['err' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()]);
        }
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/soal'), $filename);
            $url = asset('uploads/soal/' . $filename);

            return response()->json(['uploaded' => true, 'url' => $url]);
        }
        return response()->json(['uploaded' => false, 'error' => ['message' => 'Upload gagal.']], 400);
    }

    public function show(Soal $soal)
    {
        return view('Role.Guru.Course.Soal.index', compact('soal'));
    }

    public function edit(Request $request, $id_soal)
    {
        $soal = Soal::findOrFail($id_soal);
        $user = auth()->user();
        $latihan = Latihan::all();
        $id_kursus = $request->query('id_kursus');
        $courses = Kursus::with('guru')->get();
        $course  = $courses->where('id_kursus', $id_kursus)->first();

        switch ($soal->id_tipe_soal) {
            case 1:
                return view('Role.Guru.Course.Soal.pilberEdit', compact('soal','user','latihan','id_kursus','courses','course'));
            case 2:
                return view('Role.Guru.Course.Soal.truefalseEdit', compact('soal','user','latihan','id_kursus','courses','course'));
            case 3:
                return view('Role.Guru.Course.Soal.essaiEdit', compact('soal','user','latihan','id_kursus','courses','course'));
            default:
                return redirect()->route('Guru.Soal.index')->with('error', 'Tipe soal tidak dikenal');
        }
    }

    public function preview(Request $request, $id_soal)
    {
        $soal = Soal::findOrFail($id_soal);
        $user = auth()->user();

        switch ($soal->id_tipe_soal) {
            case 1: return view('Role.Guru.Course.Soal.pilberPreview', compact('soal', 'user'));
            case 2: return view('Role.Guru.Course.Soal.truefalsePreview', compact('soal', 'user'));
            case 3: return view('Role.Guru.Course.Soal.essaiPreview', compact('soal', 'user'));
            default: return redirect()->route('Guru.Soal.index')->with('error', 'Tipe soal tidak dikenal');
        }
    }

    public function update(Request $request, $id_soal)
    {
        Log::info('Menerima request untuk memperbarui soal.');

        $validated = $request->validate([
            'soal'          => 'required|string',
            'id_tipe_soal'  => 'required|exists:tipe_soal,id_tipe_soal',
            'id_latihan'    => 'nullable|exists:latihan,id_latihan',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jawaban_1'     => 'required|string|max:30',
            'jawaban_2'     => 'nullable|string|max:30',
            'jawaban_3'     => 'nullable|string|max:30',
            'jawaban_4'     => 'nullable|string|max:30',
            'jawaban_5'     => 'nullable|string|max:30',
            'correct_answer'=> 'required|string',
            'bobot'         => 'nullable|numeric|min:0',
        ]);

        $soal = Soal::findOrFail($id_soal);

        // simpan state lama (untuk re-balance kalau pindah group)
        $old_id_tipe_soal = (int)$soal->id_tipe_soal;
        $old_id_ujian     = $soal->id_ujian ? (int)$soal->id_ujian : null;
        $old_id_latihan   = $soal->id_latihan ? (int)$soal->id_latihan : null;

        DB::beginTransaction();
        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imagePath = public_path('images');
                if (!is_dir($imagePath)) mkdir($imagePath, 0755, true);

                if ($soal->image) {
                    $oldImagePath = public_path('images/' . $soal->image);
                    if (file_exists($oldImagePath)) @unlink($oldImagePath);
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move($imagePath, $imageName);
                $imageUrl  = url('images/' . $imageName);

                $soal->image     = $imageName;
                $soal->image_url = $imageUrl;
            }

            $soal->update([
                'soal'         => $validated['soal'],
                'image'        => $soal->image ?? $soal->image,
                'image_url'    => $soal->image_url ?? $soal->image_url,
                'id_tipe_soal' => $validated['id_tipe_soal'],
                'id_latihan'   => $validated['id_latihan'] ?? $soal->id_latihan,
                // untuk PG/BS bobot akan diatur otomatis; untuk Essay bisa tetap pakai $validated['bobot']
                'bobot'        => in_array((int)$validated['id_tipe_soal'], self::TIPE_PG_BS, true)
                                  ? $soal->bobot
                                  : ($validated['bobot'] ?? $soal->bobot),
            ]);

            // bangun ulang jawaban
            $jawaban_data = [];
            if ((int)$validated['id_tipe_soal'] === 1) {
                $jawaban_data = [
                    ['jawaban'=>$validated['jawaban_1'],'benar'=>$validated['correct_answer']==='jawaban_1','id_soal'=>$soal->id_soal,'id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_2'],'benar'=>$validated['correct_answer']==='jawaban_2','id_soal'=>$soal->id_soal,'id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_3'],'benar'=>$validated['correct_answer']==='jawaban_3','id_soal'=>$soal->id_soal,'id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_4'],'benar'=>$validated['correct_answer']==='jawaban_4','id_soal'=>$soal->id_soal,'id_tipe_soal'=>1],
                    ['jawaban'=>$validated['jawaban_5'],'benar'=>$validated['correct_answer']==='jawaban_5','id_soal'=>$soal->id_soal,'id_tipe_soal'=>1],
                ];
            } elseif ((int)$validated['id_tipe_soal'] === 2) {
                $jawaban_data = [
                    ['jawaban'=>$validated['jawaban_1'],'benar'=>$validated['correct_answer']==='jawaban_1','id_soal'=>$soal->id_soal,'id_tipe_soal'=>2],
                    ['jawaban'=>$validated['jawaban_2'],'benar'=>$validated['correct_answer']==='jawaban_2','id_soal'=>$soal->id_soal,'id_tipe_soal'=>2],
                ];
            } elseif ((int)$validated['id_tipe_soal'] === 3) {
                $jawaban_data = [
                    ['jawaban'=>$validated['jawaban_1'],'benar'=>true,'id_soal'=>$soal->id_soal,'id_tipe_soal'=>3],
                ];
            }
            $soal->jawaban_soal()->delete();
            $soal->jawaban_soal()->createMany($jawaban_data);

            // === Re-balance bobot otomatis untuk PG & BS ===
            // Rebalance group lama jika sebelumnya PG/BS
            if (in_array($old_id_tipe_soal, self::TIPE_PG_BS, true)) {
                if ($old_id_ujian)   { $this->rebalanceBobotUjian($old_id_ujian); }
                if ($old_id_latihan) { $this->rebalanceBobotLatihan($old_id_latihan); }
            }
            // Rebalance group baru jika sekarang PG/BS
            if (in_array((int)$soal->id_tipe_soal, self::TIPE_PG_BS, true)) {
                if ($soal->id_ujian)   { $this->rebalanceBobotUjian((int)$soal->id_ujian); }
                if ($soal->id_latihan) { $this->rebalanceBobotLatihan((int)$soal->id_latihan); }
            }

            DB::commit();

            return redirect()->route('Guru.Soal.index', [
                'id_ujian'   => $soal->id_ujian ?? null,
                'id_latihan' => $soal->id_latihan ?? null
            ])->with('success', 'Soal berhasil diperbarui & bobot disesuaikan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal update soal', ['err' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id_soal)
    {
        try {
            $soal = Soal::findOrFail($id_soal);

            $idUjian   = $soal->id_ujian;
            $idLatihan = $soal->id_latihan;
            $old_tipe  = (int)$soal->id_tipe_soal;

            if ($soal->image) {
                @unlink(public_path('images/' . $soal->image));
            }

            $soal->delete();

            // === Re-balance bobot otomatis untuk PG & BS (setelah hapus) ===
            if (in_array($old_tipe, self::TIPE_PG_BS, true)) {
                if ($idLatihan) {
                    $this->rebalanceBobotLatihan((int)$idLatihan);
                } elseif ($idUjian) {
                    $this->rebalanceBobotUjian((int)$idUjian);
                }
            }

            if ($idLatihan) {
                return redirect()->route('Guru.Soal.index', ['id_latihan' => $idLatihan])
                    ->with('success', 'Soal latihan berhasil dihapus & bobot diperbarui.');
            } elseif ($idUjian) {
                return redirect()->route('Guru.Soal.index', ['id_ujian' => $idUjian])
                    ->with('success', 'Soal ujian berhasil dihapus & bobot diperbarui.');
            }

            return redirect()->route('Guru.Soal.index')->with('error', 'Soal tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus soal.']);
        }
    }

    // ========= HELPERS =========

    /**
     * Ratakan bobot untuk semua soal PG & BS di UJIAN tertentu (per tipe).
     * - Untuk tiap tipe (1 & 2), bobot = 100 / jumlah_soal_tipe
     * - Dibulatkan 2 desimal.
     */
    private function rebalanceBobotUjian(int $idUjian): void
    {
        foreach (self::TIPE_PG_BS as $tipe) {
            $count = Soal::where('id_ujian', $idUjian)->where('id_tipe_soal', $tipe)->count();
            if ($count > 0) {
                $bobot = round(100 / $count, 2);
                Soal::where('id_ujian', $idUjian)->where('id_tipe_soal', $tipe)->update(['bobot' => $bobot]);
            }
        }
    }

    /**
     * Ratakan bobot untuk semua soal PG & BS di LATIHAN tertentu (per tipe).
     * - Untuk tiap tipe (1 & 2), bobot = 100 / jumlah_soal_tipe
     * - Dibulatkan 2 desimal.
     */
    private function rebalanceBobotLatihan(int $idLatihan): void
    {
        foreach (self::TIPE_PG_BS as $tipe) {
            $count = Soal::where('id_latihan', $idLatihan)->where('id_tipe_soal', $tipe)->count();
            if ($count > 0) {
                $bobot = round(100 / $count, 2);
                Soal::where('id_latihan', $idLatihan)->where('id_tipe_soal', $tipe)->update(['bobot' => $bobot]);
            }
        }
    }

    // ====== (kode lama untuk nilai_per_soal masih ada jika kamu pakai) ======

    protected function updateNilaiPerSoalUjian($idUjian)
    {
        $jumlahSoal = Soal::where('id_ujian', $idUjian)->count();
        $ujian = Ujian::find($idUjian);
        $nilaiPerSoal = $jumlahSoal > 0 ? ($ujian->grade ?? 100) / $jumlahSoal : 0;
        Soal::where('id_ujian', $idUjian)->update(['nilai_per_soal' => $nilaiPerSoal]);

        return $nilaiPerSoal;
    }

    protected function updateNilaiPerSoalLatihan($idLatihan)
    {
        $jumlahSoal = Soal::where('id_latihan', $idLatihan)->count();
        $nilaiPerSoalLatihan = $jumlahSoal > 0 ? 100 / $jumlahSoal : 0;
        Soal::where('id_latihan', $idLatihan)->update(['nilai_per_soal' => $nilaiPerSoalLatihan]);

        return $nilaiPerSoalLatihan;
    }
}
