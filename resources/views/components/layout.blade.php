<!DOCTYPE html>
<html lang="it" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Hub Marketing' }} · Hub Marketing</title>
    <script>
        try { if (localStorage.getItem('hub-theme') === 'light') document.documentElement.dataset.theme = 'light'; } catch (e) {}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
<div class="flex min-h-screen" x-data="{ menu: false }">
    {{-- Barra laterale, contesto portafoglio (docs/mappa-applicazione.md) --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 -translate-x-full border-r border-line bg-surface transition-transform lg:static lg:translate-x-0"
           :class="menu && 'translate-x-0'">
        <div class="flex h-16 items-center gap-3 border-b border-line px-5">
            <span class="grid size-8 place-items-center rounded-md bg-accent text-sm font-bold text-white">NS</span>
            <div class="leading-tight">
                <div class="text-sm font-semibold">Hub Marketing</div>
                <div class="text-xs text-muted">Network Service</div>
            </div>
        </div>
        @php
            $nav = [
                ['Overview', 'overview', true, null],
                ['Clienti', 'clients', true, null],
                ['Allerte', 'alerts', true, null],
                ['Stato attività', null, false, 'Fase 2'],
                ['Vista operatore', null, false, 'MVP-1'],
                ['Analisi AI', null, false, 'Fase 3'],
            ];
        @endphp
        <nav class="flex flex-col gap-1 p-3 text-sm" aria-label="Navigazione principale">
            <div class="px-3 pb-1 pt-2 text-[11px] font-medium uppercase tracking-wider text-muted">Portafoglio</div>
            @foreach ($nav as [$label, $route, $enabled, $phase])
                @if ($enabled)
                    <a href="{{ route($route) }}"
                       @class(['rounded-md px-3 py-2 transition',
                               'bg-accent-soft font-medium text-accent' => request()->routeIs($route),
                               'hover:bg-surface-2' => ! request()->routeIs($route)])
                       @if (request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
                @else
                    <span class="flex items-center justify-between rounded-md px-3 py-2 text-muted/70" aria-disabled="true">
                        {{ $label }} <span class="rounded bg-surface-2 px-1.5 py-0.5 text-[10px]">{{ $phase }}</span>
                    </span>
                @endif
            @endforeach
            @can('admin')
                <div class="px-3 pb-1 pt-4 text-[11px] font-medium uppercase tracking-wider text-muted">Amministrazione</div>
                <a href="{{ route('settings') }}"
                   @class(['rounded-md px-3 py-2 transition',
                           'bg-accent-soft font-medium text-accent' => request()->routeIs('settings'),
                           'hover:bg-surface-2' => ! request()->routeIs('settings')])>Impostazioni</a>
            @endcan
        </nav>
    </aside>
    <div class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-show="menu" x-cloak @click="menu = false"></div>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex h-16 items-center justify-between gap-4 border-b border-line bg-surface px-4 sm:px-6">
            <button type="button" class="rounded-md p-2 hover:bg-surface-2 lg:hidden" @click="menu = true" aria-label="Apri il menu">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="hidden text-sm text-muted sm:block">Dati aggiornati una volta al giorno</div>
            <div class="flex items-center gap-2 text-sm" x-data="{ theme: window.hubTheme?.current() ?? 'dark' }">
                <button type="button" class="rounded-md px-3 py-1.5 hover:bg-surface-2"
                        @click="theme = window.hubTheme.toggle()"
                        x-text="theme === 'light' ? 'Tema scuro' : 'Tema chiaro'">Tema chiaro</button>
                <span class="hidden text-muted sm:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-md border border-line px-3 py-1.5 hover:bg-surface-2">Esci</button>
                </form>
            </div>
        </header>
        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
