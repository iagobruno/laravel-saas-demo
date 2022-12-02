@php
    $sub = Auth::user()?->subscription('default');
@endphp

@if (Auth::check() &&
    isset($sub) &&
    $sub->canceled() &&
    $sub->onGracePeriod() &&
    $sub->ends_at?->diffInDays(now()) <= 5)
    <x-alert-flash
        message="Sua assinatura está próxima de acabar. Reative-a para continuar utilizando nossos serviços."
        type="error"
        :dismissible="false"
        :withIcon="true"
        class="m-0" />
@endif
