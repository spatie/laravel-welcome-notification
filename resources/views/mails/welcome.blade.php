<h1>Welcome to <a href="{{ config('app.url') }}">{{ config('app.name') }}</a></h1>
<p>
    Hi,
</p>
<p>
    Click the link below to set a password
</p>
<table>
    <tr>
        <td>
            <p>
                <a href="{{ $showWelcomeFormUrl }}" class="btn-primary">
                    Set a password
                </a>
            </p>
        </td>
    </tr>
</table>

<p>
    <em>This link is valid until {{ now()->addMinutes(config('auth.passwords.users.expire'))->format('Y/m/d') }}.</em>
<
    /p>
