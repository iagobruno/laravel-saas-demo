<header class="bg-white shadow">
    <div class="mx-auto flex h-[64px] max-w-5xl items-center justify-between gap-4 px-4">
        <a href="{{ route('landing') }}" class="text-lg font-bold text-blue-500">{{ config('app.name') }}</a>

        <div class="flex items-center gap-3">
            @auth
                {{ Auth::user()->email }}
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="rounded bg-neutral-500/80 px-2 py-0.5 text-sm text-white">Sair</button>
                </form>
            @else
                @if (!Route::is('landing'))
                    <a href="{{ route('landing') }}" class="rounded-md bg-blue-500 px-3 py-1 text-white">Entrar</a>
                @endif
            @endauth
        </div>
    </div>
</header>
