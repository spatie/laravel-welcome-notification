<form method="POST" action="{{ route('welcome.save-password') }}">
    @csrf

    <input type="hidden" name="email" value="{{ $user->email }}"/>
    <input type="hidden" name="token" value="{{ $token }}"/>

    <div>
        <label for="password">{{ __('Password') }}</label>

        <div>
            <input id="password" type="password" class="@error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password">

            @error('password')
            <span>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div>
        <label for="password-confirm">{{ __('Confirm Password') }}</label>

        <div>
            <input id="password-confirm" type="password" name="password_confirmation" required
                   autocomplete="new-password">
        </div>
    </div>

    <div>
        <button type="submit">
            {{ __('Save password and login') }}
        </button>
    </div>
</form>
