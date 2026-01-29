<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.index'); // Pastikan ini bukan route change-password
        }
        
        
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::login($user);

        return redirect()->route('admin.index')->with('success', 'Pendaftaran berhasil!');
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(6)],
        ]);
    
        $user = Auth::user();
    
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama salah.'
            ]);
        }
    
        // Pilih salah satu cara di bawah:
    
        // Cara 1: Assign langsung + save()
        // $user->password = Hash::make($request->new_password);
        // $user->save();
    
        // atau Cara 2: Gunakan update() via query builder
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
    
        return redirect()->route('admin.index')->with('success', 'Password berhasil diubah!');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Tampilkan form reset password
public function showForgotPasswordForm()
{
    return view('auth.forgot-password');
}

// Proses reset password
public function resetPasswordWithoutEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => ['required', 'confirmed', Password::min(6)],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
}





}

