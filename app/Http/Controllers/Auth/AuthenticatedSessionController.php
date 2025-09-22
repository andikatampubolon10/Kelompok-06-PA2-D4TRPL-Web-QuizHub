<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\guru;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function index()
{
    return response()
        ->view('dashboard')
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
}

public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->withHeaders([
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
}

    public function create(): View
    {
        return view('login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->hasRole('Admin')) {
        return redirect()->route('Admin.Akun.index');
    } elseif ($user->hasRole('Guru')) {
        return redirect()->route('Guru.Course.index');
    } elseif ($user->hasRole('Siswa')) {
        return redirect()->route('Siswa.Course.index');
    } elseif ($user->hasRole('Operator')) {
        return redirect()->route('Operator.Kurikulum.index');
    }

    return redirect()->route('login');
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout user
        Auth::guard('web')->logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return redirect('/');
    }
}
