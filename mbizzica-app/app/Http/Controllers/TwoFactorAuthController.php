<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;

class TwoFactorSetupController extends Controller
{
    public function showSetupForm()
    {
        $user = Auth::user();
    }
}
