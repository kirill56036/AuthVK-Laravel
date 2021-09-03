<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Socialite;
use Auth;

class AuthController extends Controller
{

    public function provider($provider)
    { 
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try { 
            $user = json_decode(json_encode(Socialite::driver($provider)->stateless()->user())); 
        } catch (\Exception $e) { 
            return redirect()->intended('/');
        }
        if(isset($user->returnUrl)) return redirect('/');
        $user = $user->user;
        $user = $this->createOrGetUser($user, $provider);
        Auth::login($user, true);
        return redirect()->intended('/');
    }

    public function createOrGetUser($user, $provider)
    {
        session_start();
        if ($provider == 'vkontakte') {
            $u = User::where('user_id', $user->id)->first();

            if ($u) {
                $username = $user->first_name.' '.$user->last_name;
               $user = User::where('user_id', $user->id)->update([
                    'name' => $username,
                    'photo' => $user->photo_200
                ]);
                $user = $u;
            } else {
                $username = $user->first_name.' '.$user->last_name;
                $user = User::create([
                    'name' => $username,
                    'user_id' => $user->id,
                    'photo' => $user->photo_200,
                    'email' => $user->email,
                    'nickname' => $user->screen_name,
                    'password' => Hash::make(time()),
                ]);
            }
        }
        return $user;
    }
}
