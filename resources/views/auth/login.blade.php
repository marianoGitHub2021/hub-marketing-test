<!DOCTYPE html>
<html lang="it" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accesso · Hub Marketing</title>
    <script>
        try { if (localStorage.getItem('hub-theme') === 'light') document.documentElement.dataset.theme = 'light'; } catch (e) {}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center px-4">
<main class="flex w-full max-w-sm flex-col gap-6 rounded-xl border border-line bg-surface p-8">
    <div class="flex items-center gap-3">
        <span class="grid size-10 place-items-center rounded-md bg-accent font-bold text-white">NS</span>
        <div>
            <h1 class="text-lg font-semibold">Hub Marketing</h1>
            <p class="text-sm text-muted">Network Service</p>
        </div>
    </div>

    @if ($errors->has('login'))
        <p class="rounded-md border border-critical/40 bg-critical/10 px-3 py-2 text-sm text-critical" role="alert">{{ $errors->first('login') }}</p>
    @endif

    <a href="{{ route('auth.google.redirect') }}"
       class="rounded-md bg-accent px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-accent-strong">
        Accedi con Google
    </a>
    <p class="text-xs text-muted">Solo account {{ '@'.config('hub.login_domain') }}.</p>

    @if ($devLogin)
        <form method="POST" action="{{ route('auth.dev') }}" class="flex flex-col gap-2 border-t border-line pt-5">
            @csrf
            <label for="dev-email" class="text-xs font-medium text-muted">Accesso di sviluppo (solo ambiente locale)</label>
            <input id="dev-email" name="email" type="email" required placeholder="{{ 'nome@'.config('hub.login_domain') }}"
                   class="rounded-md border border-line bg-bg px-3 py-2 text-sm">
            @error('email') <p class="text-xs text-critical">{{ $message }}</p> @enderror
            <button type="submit" class="rounded-md border border-line px-3 py-2 text-sm hover:bg-surface-2">Entra</button>
        </form>
    @endif
</main>
</body>
</html>
