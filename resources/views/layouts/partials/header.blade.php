<header class="border-b border-b-neutral-300/70 bg-white shadow">
    <div class="mx-auto flex h-[64px] max-w-5xl items-center justify-between gap-4 px-4">
        <a href="{{ route('landing') }}" class="text-lg font-bold text-blue-500">{{ config('app.name') }}</a>

        <div class="flex flex-1 items-center gap-3 text-sm">
            <a href="{{ route('prices') }}" class="hover:underline">Planos e preços</a>
        </div>

        <div class="flex items-center gap-3 text-sm">
            @auth
                <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Minha conta</a>
                {{ Auth::user()->email }}
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="rounded bg-neutral-500/80 px-1.5 py-0.5 text-xs text-white">Sair</button>
                </form>
            @else
                @if (!Route::is('landing'))
                    <a href="{{ route('landing') }}" class="rounded-md bg-blue-500 px-3 py-1 text-white">Entrar</a>
                @endif
            @endauth
        </div>
    </div>
</header>
