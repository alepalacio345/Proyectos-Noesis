<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Tasa de Cambio (BCV)</h2>
            <p class="text-sm text-slate-500 mt-1">Sincroniza la tasa diaria oficial o ajústala manualmente para tus ventas.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200/60 shadow-sm flex items-start">
            <svg class="h-5 w-5 text-emerald-500 mt-0.5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <h3 class="text-sm font-semibold text-emerald-800">Éxito</h3>
                <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-xl bg-rose-50 p-4 border border-rose-200/60 shadow-sm flex items-start">
            <svg class="h-5 w-5 text-rose-500 mt-0.5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <div>
                <h3 class="text-sm font-semibold text-rose-800">Error de Conexión</h3>
                <p class="text-sm text-rose-700 mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Tarjeta de Tasa Actual -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                <div class="p-6 sm:p-8">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Tasa Activa de Hoy</h3>
                    
                    @if($currentRate)
                        <div class="flex items-end gap-2 mt-4">
                            <span class="text-4xl font-black text-slate-900 tracking-tight">Bs. {{ number_format($currentRate->rate, 2, ',', '.') }}</span>
                            <span class="text-slate-500 font-medium mb-1">/ USD</span>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg inline-flex font-semibold">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Actualizada para hoy
                        </div>
                    @else
                        <div class="flex items-end gap-2 mt-4">
                            <span class="text-4xl font-black text-slate-300 tracking-tight">Bs. 0,00</span>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-rose-600 bg-rose-50 px-3 py-1.5 rounded-lg inline-flex font-semibold">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tasa no definida hoy
                        </div>
                    @endif

                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <button wire:click="syncWithBCV" wire:loading.attr="disabled" class="w-full group inline-flex justify-center items-center px-5 py-3 bg-slate-900 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 shadow-[0_4px_14px_0_rgb(0,0,0,0.1)] hover:shadow-[0_4px_14px_0_rgb(79,70,229,0.39)] transition-all duration-300 disabled:opacity-70">
                            <svg wire:loading.remove wire:target="syncWithBCV" class="mr-2 h-5 w-5 text-slate-300 group-hover:text-indigo-100 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            
                            <svg wire:loading wire:target="syncWithBCV" class="animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            
                            <span wire:loading.remove wire:target="syncWithBCV">Sincronizar con BCV</span>
                            <span wire:loading wire:target="syncWithBCV">Sincronizando...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tasa Manual -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mt-6">
                <div class="p-6 sm:p-8">
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Ajuste Manual</h3>
                    <p class="text-xs text-slate-500 mb-5">Útil si la API del BCV está caída o necesitas forzar otra tasa.</p>
                    
                    <form wire:submit.prevent="saveManualRate">
                        <div>
                            <label class="block text-xs font-semibold leading-6 text-slate-500 uppercase tracking-wider">Tasa Manual (Bs. por USD)</label>
                            <div class="mt-1 relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm font-semibold">Bs.</span>
                                </div>
                                <input type="number" step="0.0001" wire:model="manualRate" class="block w-full rounded-xl border-0 py-2.5 pl-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm font-bold placeholder:font-normal" placeholder="Ej. 36.5400">
                            </div>
                            @error('manualRate') <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-300 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors shadow-sm">
                                Guardar Manualmente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Historial -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-base font-bold text-slate-900">Historial de Tasas</h3>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tasa Registrada</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Estatus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($history as $record)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-slate-900">
                                {{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-slate-700 font-mono">
                                Bs. {{ number_format($record->rate, 4, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                @if($record->date === date('Y-m-d'))
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Activa</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">Histórico</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-500">
                                No hay tasas registradas en el historial.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($history->hasPages())
            <div class="bg-slate-50/50 px-6 py-4 border-t border-slate-100">
                {{ $history->links() }}
            </div>
            @endif
        </div>
    </div>
</div>