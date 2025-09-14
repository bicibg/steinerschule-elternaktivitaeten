<!DOCTYPE html>
<html>
<head>
    <title>Debug</title>
</head>
<body>
    <h1>Debug Information</h1>
    <p>Laravel Version: {{ app()->version() }}</p>
    <p>PHP Version: {{ phpversion() }}</p>
    <p>Auth Check: {{ Auth::check() ? 'Logged In' : 'Not Logged In' }}</p>
    <p>Environment: {{ app()->environment() }}</p>
    <p>Debug Mode: {{ config('app.debug') ? 'On' : 'Off' }}</p>
    <p>Timestamp: {{ now() }}</p>

    <h2>Header Test</h2>
    <div style="border: 1px solid red; padding: 10px;">
        <div class="flex items-center space-x-2">
            <p>This div should contain auth buttons:</p>
            @if(!Auth::check())
                <a href="{{ route('login') }}" style="border: 1px solid blue; padding: 5px;">Anmelden</a>
                <form action="{{ route('demo.login') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: green; color: white; padding: 5px;">DEMO</button>
                </form>
                <a href="{{ route('register') }}" style="border: 1px solid blue; padding: 5px;">Registrieren</a>
            @else
                <span>Logged in as: {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="border: 1px solid gray; padding: 5px;">Abmelden</button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>