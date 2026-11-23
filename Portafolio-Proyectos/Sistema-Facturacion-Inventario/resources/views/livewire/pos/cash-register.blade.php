<div>

    {{-- ── Flash Messages ─────────────────────────────────────────────────── --}}
    @if ($successMessage)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-2 rounded-2xl bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 border border-emerald-100 shadow-sm">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ $successMessage }}
    </div>
    @endif

    @if ($errorMessage)
    <div class="mb-4 flex items-center gap-2 rounded-2xl bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 border border-rose-100 shadow-sm">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $errorMessage }}
    </div>
    @endif

    {{-- ── Main 2-Column Layout ────────────────────────────────────────────── --}}
    <div class="flex gap-5 flex-1 min-h-0">

        {{-- ════ LEFT: Product Search ════ --}}
        <div class="flex-1 flex flex-col gap-4 min-w-0">

            {{-- Search bar --}}
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-slate-100 p-4 flex items-center gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.250ms="search" 
                           wire:keydown.enter="scanProduct"
                           type="text"
                           class="block w-full pl-10 pr-3 py-2.5 border-0 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-indigo-600 rounded-xl sm:text-sm"
                           placeholder="Escanear código de barras o buscar..." autofocus>
                </div>
                @if ($bcvRate > 0)
                <div class="shrink-0 flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-2 rounded-xl border border-indigo-100">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Bs. {{ number_format($bcvRate, 2, ',', '.') }}
                </div>
                @endif
            </div>

            {{-- Product grid --}}
            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                    @forelse ($products as $product)
                    <button wire:click="addToCart({{ $product->id }})"
                            wire:loading.attr="disabled"
                            class="group bg-white rounded-2xl border border-slate-100 p-4 text-left hover:border-indigo-300 hover:shadow-md hover:shadow-indigo-500/10 hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold text-lg mb-3">
                            {{ strtoupper(substr($product->name, 0, 1)) }}
                        </div>
                        <p class="text-sm font-bold text-slate-800 leading-snug line-clamp-2 mb-2">{{ $product->name }}</p>
                        <p class="text-base font-black text-indigo-700">${{ number_format($product->sale_price, 2) }}</p>
                        @if ($bcvRate > 0)
                        <p class="text-[11px] text-slate-400 font-medium">
                            Bs. <span x-text="({{ $product->sale_price }} * bcvRate).toLocaleString('es-VE',{minimumFractionDigits:2,maximumFractionDigits:2})"></span>
                        </p>
                        @endif
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-md
                                {{ $product->stock <= $product->min_stock ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-slate-100 text-slate-500' }}">
                                {{ $product->stock }} disponibles
                            </span>
                        </div>
                    </button>
                    @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                        <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        <p class="text-sm font-semibold text-slate-500">No se encontraron productos con stock</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ════ RIGHT: Cart & Order Summary ════ --}}
        <div class="w-80 xl:w-96 flex flex-col bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-100 overflow-hidden shrink-0">

            {{-- Cart header --}}
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <h3 class="font-bold text-slate-900">Orden Actual</h3>
                    @if (!empty($cart))
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold">
                        {{ count($cart) }}
                    </span>
                    @endif
                </div>
                @if (!empty($cart))
                <button wire:click="clearCart" class="text-xs text-slate-400 hover:text-rose-500 transition-colors font-medium">
                    Limpiar
                </button>
                @endif
            </div>

            {{-- Cart items --}}
            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-2">
                @forelse ($cart as $i => $item)
                <div class="flex items-start gap-3 bg-slate-50/50 rounded-2xl p-3 border border-slate-100">
                    <div class="h-9 w-9 shrink-0 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                        {{ strtoupper(substr($item['name'], 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $item['name'] }}</p>
                        <p class="text-xs text-slate-500">${{ number_format($item['price'], 2) }} c/u</p>
                        {{-- Qty controls --}}
                        <div class="flex items-center gap-2 mt-1.5">
                            <button wire:click="updateQty({{ $i }}, {{ $item['qty'] - 1 }})"
                                    class="h-6 w-6 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-sm font-bold transition-colors">−</button>
                            <span class="text-sm font-bold text-slate-900 w-6 text-center">{{ $item['qty'] }}</span>
                            <button wire:click="updateQty({{ $i }}, {{ $item['qty'] + 1 }})"
                                    class="h-6 w-6 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-sm font-bold transition-colors">+</button>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-black text-slate-900">${{ number_format($item['subtotal'], 2) }}</p>
                        @if ($bcvRate > 0)
                        <p class="text-[10px] text-slate-400">
                            <span x-text="'Bs. ' + ({{ $item['subtotal'] }} * bcvRate).toLocaleString('es-VE',{minimumFractionDigits:2,maximumFractionDigits:2})"></span>
                        </p>
                        @endif
                        <button wire:click="removeFromCart({{ $i }})" class="text-rose-400 hover:text-rose-600 mt-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-32 text-center">
                    <svg class="w-10 h-10 text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p class="text-xs font-semibold text-slate-400">Agrega productos desde la izquierda</p>
                </div>
                @endforelse
            </div>

            {{-- Order total --}}
            @if (!empty($cart))
            <div class="border-t border-slate-100 px-5 py-4 bg-slate-50/50 space-y-1">
                <div class="flex justify-between text-sm text-slate-500">
                    <span>{{ count($cart) }} {{ count($cart) === 1 ? 'artículo' : 'artículos' }}</span>
                    <span class="font-semibold">${{ number_format($totalUsd, 2) }}</span>
                </div>
                @if ($bcvRate > 0)
                <div class="flex justify-between text-xs text-slate-400">
                    <span>Equivalente Bs.</span>
                    <span x-text="'Bs. ' + ({{ $totalBs }} ).toLocaleString('es-VE',{minimumFractionDigits:2,maximumFractionDigits:2})"></span>
                </div>
                @endif
            </div>
            @endif

            {{-- Charge button --}}
            <div class="px-4 pb-4 pt-2">
                <button wire:click="openPayment"
                        @if(empty($cart)) disabled @endif
                        class="w-full flex items-center justify-center gap-2 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-200
                               {{ empty($cart) ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-500 shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/40' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Cobrar — ${{ number_format($totalUsd, 2) }}
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════ PAYMENT SLIDE-OVER ════════════ --}}
    @if ($showPayment)
    <div class="relative z-50">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closePayment"></div>

        {{-- Panel --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-sm">
                        <div class="flex h-full flex-col bg-white shadow-2xl overflow-y-auto">

                            {{-- Header --}}
                            <div class="bg-slate-900 px-6 py-5 relative overflow-hidden">
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/20 rounded-full blur-2xl"></div>
                                <div class="relative z-10 flex justify-between items-start">
                                    <div>
                                        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Total a Cobrar</p>
                                        <p class="text-3xl font-black text-white">${{ number_format($totalUsd, 2) }}</p>
                                        @if ($bcvRate > 0)
                                        <p class="text-slate-400 text-sm mt-0.5">Bs. {{ number_format($totalBs, 2, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    <button wire:click="closePayment" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex-1 px-5 py-5 space-y-5">

                                {{-- Add payment method --}}
                                <div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Agregar Pago</h4>
                                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/60 space-y-3">

                                        {{-- Method selector --}}
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach(['cash_usd' => 'Efectivo USD', 'cash_bs' => 'Efectivo Bs.', 'pago_movil' => 'Pago Móvil', 'zelle' => 'Zelle'] as $m => $label)
                                            <button wire:click="$set('payMethod', '{{ $m }}')"
                                                    class="flex items-center justify-center px-3 py-2.5 rounded-xl text-xs font-semibold border transition-all
                                                           {{ $payMethod === $m ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300' }}">
                                                {{ $label }}
                                            </button>
                                            @endforeach
                                        </div>

                                        {{-- Amount --}}
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-slate-500 font-bold text-sm pointer-events-none">
                                                {{ in_array($payMethod, ['cash_usd', 'zelle']) ? '$' : 'Bs.' }}
                                            </span>
                                            <input type="number" step="0.01" wire:model="payAmount"
                                                   class="block w-full rounded-xl border-0 py-2.5 pl-9 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm font-mono shadow-sm"
                                                   placeholder="0.00">
                                        </div>

                                                                                {{-- Account Selector (For National Methods) --}}
                                        @if (in_array($payMethod, ['pago_movil', 'transfer', 'pos_terminal']))
                                            @php
                                                $availableAccounts = collect($businessAccounts ?? [])->where('method', $payMethod);
                                            @endphp
                                            
                                            @if($availableAccounts->isNotEmpty())
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cuenta Destino</label>
                                                <select wire:model.live="selectedAccountId" class="block w-full rounded-xl border-0 py-2.5 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                                    <option value="">-- Seleccionar cuenta --</option>
                                                    @foreach($availableAccounts as $acc)
                                                        <option value="{{ $acc->id }}">{{ $acc->label }} ({{ $acc->bank }})</option>
                                                    @endforeach
                                                </select>

                                                {{-- Show Account Details to Dictate to Customer --}}
                                                @if($selectedAccountId)
                                                    @php $selectedAcc = $availableAccounts->firstWhere('id', $selectedAccountId); @endphp
                                                    @if($selectedAcc)
                                                    <div class="mt-2 bg-indigo-50 border border-indigo-100 rounded-xl p-3 text-sm">
                                                        <p class="font-bold text-indigo-900 mb-1">Datos para el cliente:</p>
                                                        <div class="space-y-1 text-indigo-800 font-mono text-xs">
                                                            @if($payMethod === 'pago_movil')
                                                                <p>Banco: <span class="font-bold">{{ $selectedAcc->bank }}</span></p>
                                                                <p>Teléfono: <span class="font-bold">{{ $selectedAcc->phone }}</span></p>
                                                                <p>Cédula/RIF: <span class="font-bold">{{ $selectedAcc->id_number }}</span></p>
                                                            @elseif($payMethod === 'transfer')
                                                                <p>Banco: <span class="font-bold">{{ $selectedAcc->bank }}</span></p>
                                                                <p>Cuenta: <span class="font-bold">{{ $selectedAcc->account_number }}</span></p>
                                                                <p>Titular: <span class="font-bold">{{ $selectedAcc->holder_name }}</span></p>
                                                                <p>Identificación: <span class="font-bold">{{ $selectedAcc->id_number }}</span></p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif
                                            </div>
                                            @else
                                                <p class="text-xs text-amber-600 font-medium">⚠️ No tienes cuentas de este tipo configuradas.</p>
                                            @endif
                                        @endif

                                        {{-- Reference --}}
                                        @if (in_array($payMethod, ['zelle', 'pago_movil', 'transfer', 'pos_terminal']))
                                        <input type="text" wire:model="payReference"
                                               class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm shadow-sm mt-3"
                                               placeholder="N° de referencia o recibo...">
                                        @endif

                                        <button wire:click="addPayment"
                                                class="w-full flex items-center justify-center gap-1.5 rounded-xl bg-slate-900 py-2.5 text-sm font-semibold text-white hover:bg-indigo-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Agregar
                                        </button>
                                    </div>
                                </div>

                                {{-- Payments list --}}
                                @if (!empty($payments))
                                <div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Pagos Ingresados</h4>
                                    <div class="space-y-2">
                                        @foreach ($payments as $pi => $p)
                                        <div class="flex items-center gap-3 bg-emerald-50 rounded-xl px-3 py-2.5 border border-emerald-100">
                                            <div class="h-7 w-7 rounded-lg bg-emerald-500 flex items-center justify-center shrink-0">
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-emerald-800">
                                                    {{ \App\Models\Payment::methodLabel($p['method']) }}
                                                </p>
                                                @if($p['reference'])
                                                <p class="text-[10px] text-emerald-600 truncate">Ref: {{ $p['reference'] }}</p>
                                                @endif
                                            </div>
                                            <p class="text-sm font-black text-emerald-800 shrink-0">
                                                {{ $p['currency'] === 'USD' ? '$' : 'Bs.' }}{{ number_format($p['amount'], 2, ',', '.') }}
                                            </p>
                                            <button wire:click="removePayment({{ $pi }})" class="text-rose-400 hover:text-rose-600 transition-colors shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Summary --}}
                                @if (!empty($payments))
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/60 space-y-2">
                                    <div class="flex justify-between text-sm text-slate-600">
                                        <span class="font-semibold">Total a Cobrar</span>
                                        <span class="font-bold">${{ number_format($totalUsd, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-slate-600">
                                        <span class="font-semibold">Total pagado</span>
                                        <span class="font-bold">${{ number_format($paidUsd, 2) }}</span>
                                    </div>
                                    <div class="border-t border-slate-200 pt-2 flex justify-between items-center">
                                        <span class="text-sm font-bold {{ $changeUsd >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                            {{ $changeUsd >= 0 ? 'Vuelto' : 'Falta' }}
                                        </span>
                                        <span class="text-lg font-black {{ $changeUsd >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                            ${{ number_format(abs($changeUsd), 2) }}
                                        </span>
                                    </div>
                                    @if ($changeUsd >= 0 && $bcvRate > 0)
                                    <p class="text-[11px] text-slate-400 text-right">
                                        Vuelto en Bs.: {{ number_format($changeUsd * $bcvRate, 2, ',', '.') }}
                                    </p>
                                    @endif
                                </div>
                                @endif

                                {{-- Note --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nota (opcional)</label>
                                    <input type="text" wire:model="note"
                                           class="block w-full rounded-xl border-0 py-2.5 px-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm shadow-sm"
                                           placeholder="Referencia, nombre cliente...">
                                </div>

                            </div>

                            {{-- Confirm button --}}
                            <div class="px-5 pb-6 pt-2 space-y-2">
                                @if ($errorMessage)
                                <p class="text-xs font-semibold text-rose-600 text-center">{{ $errorMessage }}</p>
                                @endif
                                <button wire:click="processSale"
                                        wire:loading.attr="disabled"
                                        @if(empty($payments) || $changeUsd < -0.01) disabled @endif
                                        class="w-full flex items-center justify-center gap-2 rounded-2xl px-4 py-4 text-sm font-bold transition-all duration-200
                                               {{ (empty($payments) || $changeUsd < -0.01) ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-emerald-600 text-white hover:bg-emerald-500 shadow-lg shadow-emerald-600/25' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span wire:loading.remove wire:target="processSale">Confirmar Venta</span>
                                    <span wire:loading wire:target="processSale">Procesando...</span>
                                </button>
                                <button wire:click="closePayment"
                                        class="w-full py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                                    Cancelar
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>