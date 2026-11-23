<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 flex flex-col justify-between relative overflow-hidden">
    <!-- Decoración de fondo -->
    <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="flex items-center gap-4 relative z-10">
        <div class="h-14 w-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 ring-1 ring-inset ring-indigo-500/20">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="flex-1">
            <div class="flex justify-between items-start">
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Tasa Oficial BCV</p>
                <button wire:click="syncWithBCV" wire:loading.attr="disabled" class="text-indigo-600 hover:text-indigo-800 p-1 bg-indigo-50 rounded-lg transition-colors group" title="Sincronizar ahora">
                    <svg wire:loading.remove wire:target="syncWithBCV" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    <svg wire:loading wire:target="syncWithBCV" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mt-1 flex items-baseline gap-1">
                @if($currentRate)
                    Bs. {{ number_format($currentRate->rate, 2, ',', '.') }}
                @else
                    <span class="text-slate-300">0,00</span>
                @endif
            </h3>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-slate-100 relative z-10 flex items-center justify-between">
        @if (session()->has('widget_success'))
            <span class="text-xs font-semibold text-emerald-600">{{ session('widget_success') }}</span>
        @elseif (session()->has('widget_error'))
            <span class="text-xs font-semibold text-rose-600">{{ session('widget_error') }}</span>
        @else
            @if($currentRate)
                <span class="text-xs font-medium text-emerald-600 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                    Actualizada hoy
                </span>
            @else
                <span class="text-xs font-medium text-rose-600 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span>
                    Falta sincronizar
                </span>
            @endif
        @endif
        
        <a href="{{ route('settings.rates') }}" class="text-xs font-semibold text-slate-400 hover:text-indigo-600 transition-colors">Ver historial &rarr;</a>
    </div>
</div>