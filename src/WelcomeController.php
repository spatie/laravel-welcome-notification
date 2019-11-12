<?php

namespace Spatie\WelcomeNotification;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Spatie\WelcomeNotification\Tests\Models\User;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController
{
    use ResetsPasswords;

    public function showWelcomeForm(Request $request, User $user, string $token = null)
    {
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
        return response()->view('welcomeNotification::invalidWelcomeLink', [], 404);
    }

    protected function sendPasswordSavedResponse(): Response
    {
        return redirect()->to($this->redirectPath())->with('status', 'Welcome! You are now logged in!');
    }

    protected function sendResetResponse(): Response
    {
        return $this->sendPasswordSavedResponse();
    }
}
