<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * Controller untuk menangani alur autentikasi (login dan logout)
 * bagi pengguna Admin dan Mahasiswa.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan formulir login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'items.index');
        }

        return view('auth.login');
    }

    public function showForgotPasswordForm()
    {
        return redirect()->route('login', ['show_forgot_password' => 1]);
    }

    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->only('email', 'nim'), [
            'email' => ['required', 'email'],
            'nim' => ['required', 'string', 'regex:/^[0-9]{8,15}$/'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex' => 'NIM harus terdiri dari 8 sampai 15 angka.',
        ]);

        $returnToModal = route('login', ['show_forgot_password' => 1]);
        if ($validator->fails()) {
            return redirect($returnToModal)
                ->withErrors($validator, 'forgotPassword')
                ->withInput($request->only('email', 'nim'));
        }

        $validated = $validator->validated();

        if (config('mail.default') === 'log') {
            return redirect($returnToModal)->with(
                'password_reset_status',
                'Layanan email belum dikonfigurasi untuk mengirim ke inbox. Hubungi administrator aplikasi.'
            );
        }

        $user = null;
        try {
            $user = User::query()
                ->where('role', 'mahasiswa')
                ->where('email', $validated['email'])
                ->where('nim', $validated['nim'])
                ->first();

            if (! $user) {
                return redirect($returnToModal)
                    ->withInput($request->only('email', 'nim'))
                    ->with('password_reset_error', 'Email dan NIM tidak sinkron atau tidak terdaftar pada sistem.')
                    ->with('password_reset_status', 'Email dan NIM tidak sinkron atau tidak terdaftar pada sistem.');
            }

            Password::sendResetLink(['email' => $user->email]);
        } catch (Throwable $exception) {
            if ($user) {
                try {
                    Password::broker()->deleteToken($user);
                } catch (Throwable) {
                }
            }

            Log::error('Password reset email delivery failed.', [
                'user_id' => $user?->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return redirect($returnToModal)
                ->withInput($request->only('email', 'nim'))
                ->with('password_reset_error', 'Email verifikasi belum dapat diproses saat ini. Silakan coba kembali beberapa saat lagi.')
                ->with('password_reset_status', 'Email verifikasi belum dapat diproses saat ini. Silakan coba kembali beberapa saat lagi.');
        }

        return redirect($returnToModal)->with(
            'password_reset_status',
            'Tautan verifikasi berhasil dikirim ke inbox email akun Anda. Silakan periksa kotak masuk atau folder spam.'
        );
    }

    public function showResetPasswordForm(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset($validated, function (User $user, string $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
                'must_change_password' => false,
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan masuk dengan password baru.');
        }

        return back()->withErrors([
            'email' => 'Tautan reset tidak valid atau sudah kedaluwarsa. Ajukan permintaan baru.',
        ]);
    }

    public function showPasswordChangeForm()
    {
        abort_unless(auth()->user()?->must_change_password, 404);

        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        abort_unless($user?->must_change_password, 404);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->password = $validated['password'];
        $user->must_change_password = false;
        $user->save();
        $request->session()->regenerate();

        return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'items.index')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Memproses percobaan masuk/login dari formulir.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Coba mencocokkan kredensial dengan database
        $remember = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->must_change_password) {
                $user->password = Str::random(64);
                $user->remember_token = Str::random(60);
                $user->save();

                return redirect()->route('password.change');
            }

            $roleLabel = $user->role === 'admin' ? 'Admin' : 'Mahasiswa';

            return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'items.index')
                ->with('success', "Selamat datang kembali, {$user->name}! Anda masuk sebagai {$roleLabel}.");
        }

        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return redirect()->route('login')->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses permintaan keluar/logout dari aplikasi.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('items.index')
                         ->with('success', 'Anda berhasil keluar dari sistem!');
    }

    /**
     * Menampilkan formulir registrasi.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses pendaftaran pengguna baru.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'regex:/^[^@\s]+@gmail\.com$/i', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nim' => ['required', 'string', 'regex:/^[0-9]{8,15}$/', 'unique:users,nim'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Pendaftaran baru harus menggunakan email pribadi Gmail.',
            'email.unique' => 'Email sudah terdaftar.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex' => 'NIM harus terdiri dari 8 sampai 15 angka.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
            'nim' => $validated['nim'],
        ];

        $user = User::create($userData);

        // Login otomatis setelah registrasi berhasil
        Auth::login($user);

        return redirect()->route('items.index')
            ->with('success', "Pendaftaran berhasil! Selamat datang, {$user->name}. Anda masuk sebagai Mahasiswa.");
    }
}
