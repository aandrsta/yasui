<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->redirectUrl(url('/auth/google/callback'))
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(url('/auth/google/callback'))
                ->user();
            
            // Check if user already has this google_id
            $existingUser = User::where('google_id', $googleUser->id)->first();
            
            if ($existingUser) {
                Auth::login($existingUser);
                if ($existingUser->isAdmin()) {
                    \App\Models\Cart::where('user_id', $existingUser->id)->delete();
                    $orderIds = \App\Models\Order::where('user_id', $existingUser->id)->pluck('id');
                    \App\Models\OrderItem::whereIn('order_id', $orderIds)->delete();
                    \App\Models\Payment::whereIn('order_id', $orderIds)->delete();
                    \App\Models\Order::where('user_id', $existingUser->id)->delete();
                }
                return redirect('/')->with('success', 'Selamat datang kembali, ' . $existingUser->name . '!');
            }
            
            // Or check if user with same email exists (but google_id is empty)
            $userByEmail = User::where('email', $googleUser->email)->first();
            
            if ($userByEmail) {
                // Link Google ID to existing account
                $userByEmail->update([
                    'google_id' => $googleUser->id,
                ]);
                
                Auth::login($userByEmail);
                if ($userByEmail->isAdmin()) {
                    \App\Models\Cart::where('user_id', $userByEmail->id)->delete();
                    $orderIds = \App\Models\Order::where('user_id', $userByEmail->id)->pluck('id');
                    \App\Models\OrderItem::whereIn('order_id', $orderIds)->delete();
                    \App\Models\Payment::whereIn('order_id', $orderIds)->delete();
                    \App\Models\Order::where('user_id', $userByEmail->id)->delete();
                }
                return redirect('/')->with('success', 'Akun Google berhasil ditautkan. Selamat datang kembali, ' . $userByEmail->name . '!');
            }
            
            // Create a brand new user
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(Str::random(16)), // secure random password
                'role' => 'user',
            ]);
            
            Auth::login($newUser);
            return redirect('/')->with('success', 'Pendaftaran berhasil menggunakan akun Google. Selamat datang, ' . $newUser->name . '!');
            
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Gagal masuk menggunakan Google. Silakan coba lagi. (' . $e->getMessage() . ')');
        }
    }
}
