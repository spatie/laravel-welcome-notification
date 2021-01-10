<?php

namespace Spatie\WelcomeNotification;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController
{
    public function showWelcomeForm(Request $request, User $user)
    {
        return view('welcomeNotification::welcome')->with([
            'email' => $request->email,
            'user' => $user,
        ]);
    }

    public function savePassword(Request $request, User $user)
    {
        $request->validate($this->rules());

        $user->password = Hash::make($request->password);
        $user->welcome_valid_until = null;
        $user->save();

        auth()->login($user);

        return $this->sendPasswordSavedResponse();
    }

    protected function sendPasswordSavedResponse(): Response
    {
        return redirect()->to($this->redirectPath())->with('status', __('Welcome! You are now logged in!'));
    }

    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
