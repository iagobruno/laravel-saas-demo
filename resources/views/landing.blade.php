@extends('layouts.main')

@section('content')
    <header class="mt-12 mb-14 py-3 text-center">
        {{-- @elseif(!Auth::user()->subscribed()) --}}
        @auth
            <h1 class="mb-7 text-4xl font-bold">Bem-vindo(a) de volta!</h1>
            <a href="{{-- {{ route('dashboard') }} --}}" class="rounded-md bg-blue-500 px-4 py-2 text-white">Ir para o painel de
                controle</a>
        @else
            <h1 class="mb-4 text-4xl font-bold">Obtenha acesso ao conteúdo</h1>
            <p class="text-base text-neutral-800">Pronto para assinar? Informe seu email para criar ou reiniciar sua assinatura.
            </p>

            {{-- O controller não tá recebendo a chamada --}}
            <form action="{{ route('login') }}" method="POST" class="mt-5">
                @csrf
                <div class="flex justify-center">
                    <input type="email" name="email" required placeholder="Email..." autocomplete="on" autocapitalize="off"
                        autocorrect="off" @class([
                            'w-[360px] rounded-l-md border border-neutral-400 bg-white px-2.5 leading-8',
                            '!border-rose-600' => $errors->any(),
                        ]) value="{{ old('email') }}">
                    <button type="submit" class="rounded-r-md bg-blue-500 px-4 py-1.5 text-white">Login</button>
                </div>
                <p @class([
                    'mt-2 text-base text-neutral-500',
                    '!text-rose-600' => $errors->any(),
                ])>
                    {{ $errors->any() ? $errors->first() : 'Digite um email aleatório para fazer login.' }}
                </p>
            </form>
        @endauth
    </header>

    <section class="mx-auto flex w-3/4 justify-center justify-evenly gap-5">
        <div class="max-w-[100%/3] flex-1 text-center">
            <h5 class="mb-3 text-xl font-bold">Assista em qualquer dispositivo</h5>
            <p class="text-neutral-700">Assista em Smart TVs, PlayStation, Xbox, Chromecast, Apple TV, aparelhos de Blu-ray
                e
                outros dispositivos.
            </p>
        </div>
        <div class="max-w-[100%/3] flex-1 text-center">
            <h5 class="mb-3 text-xl font-bold">Baixe para assistir offline</h5>
            <p class="text-neutral-700">Salve seus títulos favoritos e sempre tenha algo para assistir.</p>
        </div>
        <div class="max-w-[100%/3] flex-1 text-center">
            <h5 class="mb-3 text-xl font-bold">Cancele quando quiser</h5>
            <p class="text-neutral-700">Você pode cancelar a sua conta com apenas dois cliques. Não há taxa de cancelamento
                –
                você pode começar ou encerrar a sua assinatura a qualquer momento.
            </p>
        </div>
    </section>
@endsection
