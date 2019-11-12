<?php

namespace Spatie\WelcomeNotification;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController
{
    use ResetsPasswords;

    public function showWelcomeForm(Request $request, string $userId, string $token = null)
    {
        if (! $user = User::find($userId)) {
            return $this->invalidLinkResponse();
        }

        if (! $this->broker()->tokenExists($user, $token)) {
            return $this->invalidLinkResponse();
        }

        return view('welcomeNotification::welcome')->with([
            'token' => $token,
            'email' => $request->email,
            'user' => $user,
        ]);
    }

    public function savePassword(Request $request)
    {
        return $this->reset($request);
    }

    protected function invalidLinkResponse()
    {
        return view('welcomeNotification::invalidWelcomeLink');
    }

    protected function sendResetResponse(): Response
    {
        return redirect()->to($this->redirectPath())->with('status', 'Welcome! You are now logged in!');
    }
}
