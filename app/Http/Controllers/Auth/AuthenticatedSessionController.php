<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginOtpMail;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate credentials but do not log in yet
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user || !\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        // Generate OTP
        $code = rand(100000, 999999);
        // Store OTP in DB
        DB::table('login_otps')->updateOrInsert(
            ['user_id' => $user->id],
            ['code' => $code, 'expires_at' => now()->addMinutes(10), 'created_at' => now(), 'updated_at' => now()]
        );
        // Store user id in session for verification
        session(['otp_user_id' => $user->id]);
        // Send OTP email
        Mail::to($user->email)->send(new LoginOtpMail($code));
        // Redirect to OTP form
        return redirect()->route('auth.otp.form');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['code' => 'required']);
        $userId = session('otp_user_id');
        $otp = DB::table('login_otps')->where('user_id', $userId)->first();
        if ($otp && $otp->code == $request->code && now()->lessThanOrEqualTo($otp->expires_at)) {
            \Auth::loginUsingId($userId);
            // Clean up
            DB::table('login_otps')->where('user_id', $userId)->delete();
            session()->forget(['otp_user_id']);
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }
    }

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return response()->json(['message' => 'Session expired. Please login again.'], 401);
        }
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        $code = rand(100000, 999999);
        DB::table('login_otps')->updateOrInsert(
            ['user_id' => $user->id],
            ['code' => $code, 'expires_at' => now()->addMinutes(10), 'created_at' => now(), 'updated_at' => now()]
        );
        Mail::to($user->email)->send(new LoginOtpMail($code));
        return response()->json(['message' => 'A new code has been sent to your email.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
