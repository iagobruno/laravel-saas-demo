@extends('layouts.main')

@section('page_title')
    Planos e preços
@endsection

@section('content')
    <div class="m-auto max-w-4xl text-center">

        <header class="mb-6 mt-7">
            <h1 class="mb-2 text-4xl font-bold">Planos e preços</h1>
            <p class="text-base text-gray-600">Começe pequeno e ajuste conforme você escala.</p>
        </header>

        <div x-data="{ selected: {{ $showMonthlyPlansByDefault ? 'false' : 'true' }} }" x-cloak>

            <div class="toggle mx-auto mb-6 flex w-fit items-center rounded-full bg-neutral-300/70 p-1">
                <div class="cursor-pointer rounded-full px-4 py-1 text-[0.92rem] font-semibold"
                    x-bind:class="{ 'bg-white/90': !selected }"
                    x-on:click="selected = false">
                    Mensal</div>
                <div class="cursor-pointer rounded-full px-4 py-1 text-[0.92rem] font-semibold"
                    x-bind:class="{ 'bg-white/90': selected }"
                    x-on:click="selected = true">
                    Anual</div>
            </div>

            <div class="flex items-start justify-center gap-5">
                @foreach ($plans as $plan)
                    <div @class([
                        'card relative flex-1 rounded-md border p-4 text-left shadow',
                        'border-sky-600 border-2 shadow-sky-200' => $highlightPlan === $plan->name,
                    ])>
                        @if ($highlightPlan === $plan->name)
                            <div
                                class="badge absolute top-0 mb-0.5 -translate-y-1/2 rounded-md bg-sky-600 py-0.5 px-2 text-[11px] text-white">
                                Mais popular</div>
                        @endif
                        <div class="name block text-lg font-semibold">{{ $plan->name }}</div>
                        <p class="desc min-h-[40px] text-sm text-neutral-600">{{ $plan->description }}</p>

                        @foreach ($plan->prices as $price)
                            <div x-show="{{ $price->recurring->interval === 'month' ? '!selected' : 'selected' }}">
                                <div class="price mt-2.5 mb-0.5 flex items-center gap-2">
                                    <div class="text-3xl font-bold">@money($price->unit_amount)</div>
                                    <span class="w-0 text-sm leading-4 text-neutral-600">
                                        {{ $price->recurring->interval === 'month' ? 'por mês' : 'por ano' }}</span>
                                </div>

                                @if ($price->recurring->interval === 'year')
                                    @php
                                        $monthlyPrice = $plan->prices[0]->unit_amount * 12;
                                        $annualPrice = $plan->prices[1]->unit_amount;
                                        $diffPercentageBetweenPrices = round((($monthlyPrice - $annualPrice) / $monthlyPrice) * 100);
                                    @endphp
                                    <div class="mr-auto w-fit text-sm text-green-600">Economize
                                        {{ $diffPercentageBetweenPrices }}%!
                                    </div>
                                @endif

                                <form action="{{ route('checkout') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="price-id" value="{{ $price->id }}">
                                    <input type="hidden" name="plan" value="{{ $plan->name }}">
                                    <input type="hidden" name="recurring-interval"
                                        value="{{ $price->recurring->interval }}">
                                    <button type="submit"
                                        class="mt-3.5 w-full rounded-md bg-sky-600 py-1 px-3 text-lg text-white shadow-none transition duration-700 hover:bg-sky-600/90 hover:shadow-lg hover:shadow-sky-200">Assinar</button>
                                </form>
                            </div>
                        @endforeach

                        <hr class="mt-5 mb-3 border-neutral-300">

                        @if ($plan->metadata->features)
                            <div class="features">
                                <div class="mb-1 text-sm">Inclui:</div>
                                <ul class="space-y-1">
                                    @php($features = explode(',', $plan->metadata->features))
                                    @foreach ($features as $feature)
                                        @if ($loop->first && !$loop->parent->first)
                                            @php($prevPlanName = $plans[$loop->parent->index - 1]->name)
                                            <li>✅ Todos os recursos do plano {{ $prevPlanName }}</li>
                                        @endif

                                        <li>✅ {{ trim($feature) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        window.onload = function() {
            const descriptions = Array.from(document.querySelectorAll('.desc'))
            const maxDescriptionHeight = Math.max(...descriptions.map(elem => elem.getBoundingClientRect().height))
            descriptions.forEach(elem => {
                elem.style.minHeight = maxDescriptionHeight + 'px'
            })
        }
    </script>

    <section class="faq mx-auto mt-16 max-w-xl">
        <h3 class="mb-6 text-center text-4xl font-bold">Perguntas frequentes</h3>

        @foreach ($faq as $question => $answer)
            <div id="accordion-open" data-accordion="open" x-data="{ open: false }">
                <h2 id="accordion-open-heading-1" x-on:click="open = !open">
                    <button type="button"
                        class="flex w-full items-center justify-between rounded-t-xl border border-b-0 border-gray-200 py-3.5 px-5 text-left font-medium hover:bg-gray-100 focus:ring-4 focus:ring-gray-200"
                        data-accordion-target="#accordion-open-body-1" aria-expanded="true"
                        aria-controls="accordion-open-body-1">
                        <span class="flex items-center">{{ $question }}</span>
                        <svg data-accordion-icon class="h-6 w-6 shrink-0 rotate-180" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </h2>
                <div id="accordion-open-body-1" x-bind:class="{ 'hidden': !open }"
                    aria-labelledby="accordion-open-heading-1"class="border border-gray-200 py-3.5 px-5 {{ !$loop->last ? 'border-b-0' : '' }}">
                    {{ $answer }}
                </div>
            </div>
        @endforeach
    </section>
@endsection
