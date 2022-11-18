@props(['type', 'message', 'withIcon' => true, 'dismissible' => true])
<div role="alert" x-data x-ref="alert"
    {{ $attributes->class([
        'border-b',
        'bg-emerald-200/80 border-emerald-400 text-emerald-800' => $type === 'success',
        'bg-rose-200 border-rose-400 text-rose-800' => $type === 'error',
        'bg-yellow-100 border-yellow-400 text-yellow-800' => $type === 'warning',
        'bg-sky-200/90 border-sky-400 text-sky-800' => $type === 'info',
    ]) }}>
    <div class="mx-auto flex max-w-5xl items-center gap-3 py-3 px-4">
        @if ($withIcon)
            @if ($type === 'success')
                <svg class="bi me-2 flex-shrink" width="20" height="20" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill"></use>
                </svg>
            @elseif ($type === 'danger')
                <svg class="bi me-2 flex-shrink" width="20" height="20" role="img" aria-label="Danger:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
            @elseif ($type === 'warning')
                <svg class="bi me-2 flex-shrink" width="20" height="20" role="img" aria-label="Warning:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
            @elseif ($type === 'info')
                <svg class="bi me-2 flex-shrink" width="20" height="20" role="img" aria-label="Info:">
                    <use xlink:href="#info-fill" />
                </svg>
            @endif
        @endif

        <div class="flex-grow">{{ $message }}</div>

        @if ($dismissible)
            <button type="button" class="-my-0.5 p-1.5 leading-[14px]" aria-label="Fechar"
                x-on:click="$refs.alert.remove()">✕</button>
        @endif
    </div>
</div>

@once
    @push('extra_body')
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </symbol>
            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
            </symbol>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </symbol>
        </svg>
    @endpush
@endonce
