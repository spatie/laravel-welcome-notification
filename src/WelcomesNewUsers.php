<?php

namespace Spatie\WelcomeNotification;

use Carbon\Carbon;
use Closure;

class WelcomesNewUsers
{
    public function handle($request, Closure $next)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'The welcome link does not have a valid signature or is expired.');
        }

        if (! $request->user) {
            abort(401, 'Could not find a user to be welcomed.');
        }

        if (is_null($request->user->welcome_valid_until)) {
            return abort(401, 'The welcome link has already been used.');
        }

        if (Carbon::create($request->user->welcome_valid_until)->isPast()) {
            return abort(401, 'The welcome link has expired.');
        }

        return $next($request);
    }
}
