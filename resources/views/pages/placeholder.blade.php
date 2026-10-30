<x-layout :title="$title">
    <div class="flex flex-col gap-8">
        <header class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold tracking-tight text-balance">{{ $title }}</h1>
            <p class="max-w-prose text-muted">{{ $intro }}</p>
        </header>

        <section class="rounded-lg border border-line bg-surface p-5">
            <h2 class="text-sm font-semibold">In arrivo in questa pagina</h2>
            <ul class="mt-3 divide-y divide-line text-sm">
                @foreach ($coming as [$what, $step])
                    <li class="flex flex-wrap items-center justify-between gap-2 py-2.5">
                        <span>{{ $what }}</span>
                        <span class="rounded bg-surface-2 px-2 py-0.5 font-mono text-xs text-muted">{{ $step }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
</x-layout>
