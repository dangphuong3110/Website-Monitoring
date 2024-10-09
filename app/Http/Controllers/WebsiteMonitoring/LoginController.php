<?php

namespace App\Http\Controllers\WebsiteMonitoring;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserTab;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isJson;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('wm.index');
        }
        else {
            $sso_url = config('app.sso_url') . '&callbackURL=' . action([LoginController::class, 'loginSSO']);
            return view('website-monitor.login.login', ['sso_url' => $sso_url]);
        }
    }

    public function loginSSO(Request $request)
    {
        if (!isset($request->code)) {
            return redirect()->intended('/website-monitoring/login');
        }

        try {
            $decoded = JWT::decode(trim($request->code), new Key(config('app.php_jwt_key'), 'HS256'));
            $account = isset($decoded->account) && isJson($decoded->account) ? json_decode($decoded->account, true) : [];

            if (isset($account['email'])) {
                $user = User::where('email', trim($account['email']))->first();
                if (!$user || !isset($user->email)) {
                    $user = new User();
                    $user->email = $account['email'];
                    $user->name = $account['fullname'];

                    // Gen password random
                    $hashed_random_password = Str::random(8);
                    $user->password = bcrypt($hashed_random_password);
                    $user->save();

                    $defaultUserTab = new UserTab();
                    $defaultUserTab->user_id = $user->id;
                    $defaultUserTab->tab_id = 1;
                    $defaultUserTab->save();
                }

                // Login auth
                Auth::login($user);

                if (Auth::check()) {
                    $request->session()->regenerate();
                    if (session()->has('intended_url')) {
                        $intendedUrl = session('intended_url');
                        session()->forget('intended_url');
                        return redirect()->to($intendedUrl);
                    }
                    return redirect()->route('wm.index');
                }
            }
        } catch (\Exception $ex) {
            return redirect()->intended('/website-monitoring/login');
        }
        return true;
    }

    function urlSSOSignout($src = '')
    {
        if (!$src) {
            $src = url('/website-monitoring/login');
        }
        return 'https://sso.inet.vn/account/signout?redirect=' . $src;
    }


    public function logout(Request $request)
    {
        Auth::logout();

        Cache::forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //dang xuat khoi SSO
        $ssoLogoutUrl = $this->urlSSOSignout(url('/website-monitoring/login'));

        return redirect($ssoLogoutUrl);
    }
}
