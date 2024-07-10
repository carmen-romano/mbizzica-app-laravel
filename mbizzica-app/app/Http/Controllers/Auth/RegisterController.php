<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FAQRCode\Google2FA;

class RegisterController extends Controller
{
    use RegistersUsers {
        register as registration;
    }

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'google2fa_secret' => $data['google2fa_secret'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $google2fa = app('pragmarx.google2fa');
        $registration_data = $request->all();
        $registration_data['google2fa_secret'] = $google2fa->generateSecretKey();

        $request->session()->flash('registration_data', $registration_data);

        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']

        );
        $secret = $registration_data['google2fa_secret'];

        return view('google2fa.register', compact('QR_Image', 'secret'));
    }

    public function completeRegistration(Request $request)
    {
        $request->merge(session('registration_data'));
        $this->registration($request);

        return redirect($this->redirectPath());
    }
}
