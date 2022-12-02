@extends('layouts.main')

@section('page_title')
    Minha conta
@endsection

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-5 text-3xl font-bold">Dashboard</h1>

        <section class="mb-5">
            <strong class="mb-1 block text-lg font-semibold">Dados pessoais</strong>
            <div class="grid w-fit grid-cols-2 gap-x-2 gap-y-1">
                <span>Email:</span>
                <span>{{ auth()->user()->email }}</span>
                <span>Conta criada em:</span>
                <span>{{ auth()->user()->created_at->format('d/m/Y') }}</span>
            </div>
        </section>

        <section class="mb-5">
            <strong class="mb-1.5 block text-lg font-semibold">Seu plano</strong>
            <div
                class="card flex flex-col items-start justify-between gap-1.5 rounded-md border border-gray-300 p-2.5 px-3.5 sm:flex-row sm:items-center">
                <div>
                    @if ($subscription->canceled())
                        <span class="rounded bg-gray-200 px-1.5 py-0.5 text-xs font-semibold text-gray-900">Cancelado</span>
                    @endif
                    <div class="text-lg font-bold">{{ $plan->product->name }}</div>
                    <div class="mb-1">
                        @money($plan->amount, $plan->currency)
                        {{ $plan->interval === 'year' ? 'por ano' : 'por mês' }}
                    </div>
                    <div class="text-xs">
                        @if ($upcomingInvoice)
                            Sua próxima cobrança será no dia
                            {{ $upcomingInvoice->date()->format('d/m/Y') }},
                            no valor de @money($upcomingInvoice->total, $upcomingInvoice->currency).
                        @elseif ($subscription->onGracePeriod())
                            Seu plano será cancelado ao fim do período de faturamento, em
                            {{ $subscription->ends_at->format('d/m/Y') }}.
                        @endif
                    </div>
                </div>
                <form action="{{ route('billing') }}" method="POST">
                    @csrf
                    <x-button type="submit" class="whitespace-nowrap text-sm">
                        {{ $subscription->onGracePeriod() ? 'Retomar assinatura' : 'Gerenciar assinatura' }}
                    </x-button>
                </form>
            </div>
        </section>

        <section class="mb-5">
            <strong class="mb-1.5 block text-lg font-semibold">Recibos</strong>
            <table class="w-full">
                <thead class="border-b border-b-2 border-gray-300/80">
                    <tr>
                        <th class="py-1.5 text-left text-sm font-semibold text-gray-600">Data</th>
                        <th class="py-1.5 text-left text-sm font-semibold text-gray-600">Preço</th>
                        <th class="py-1.5 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="py-1.5 text-left text-sm font-semibold text-gray-600">Número do pedido</th>
                        <th class="py-1.5 text-left text-sm font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr class="border-b-300 border-b">
                            <td class="py-2 text-sm font-semibold">
                                {{ $invoice->date()->format('d/m/Y') }}</td>
                            <td class="py-2 text-sm">@money($invoice->total, $invoice->currency)</td>
                            <td class="py-2 text-sm">
                                <div @class([
                                    'inline-block rounded px-1 text-xs leading-5',
                                    'bg-green-200 text-green-800' => $invoice->paid,
                                    'bg-red-200 text-red-800' => !$invoice->paid,
                                ])>
                                    {{ $invoice->paid ? 'Paga' : 'Com problema' }}
                                </div>
                            </td>
                            <td class="py-2 text-xs text-gray-700/90">{{ $invoice->id }}</td>
                            <td class="py-2 text-[0.95rem]">
                                <a href="{{ $invoice->hosted_invoice_url }}" class="text-sm text-gray-600/90 underline"
                                    target="_blank"
                                    rel="noopener noreferrer">Ver
                                    detalhes</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection
