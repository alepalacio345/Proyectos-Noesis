<div wire:poll.300s>
    <a href="{{ route('settings.rates') }}" class="group flex items-center px-3 py-1.5 rounded-full transition-colors {{ $rate ? 'bg-indigo-50 hover:bg-indigo-100' : 'bg-rose-50 hover:bg-rose-100 animate-pulse' }}">
        @if($rate)
            <svg class="h-4 w-4 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-bold text-indigo-900 tracking-tight">BCV: Bs. {{ number_format($rate, 2, ',', '.') }}</span>
        @else
            <svg class="h-4 w-4 text-rose-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="text-xs font-bold text-rose-800 tracking-tight">Tasa No Fijada</span>
        @endif
    </a>
</div>