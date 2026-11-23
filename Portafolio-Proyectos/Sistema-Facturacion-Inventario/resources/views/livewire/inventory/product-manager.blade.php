<div x-data="{ isEditing: @entangle('isEditing'), bcvRate: {{ $bcvRate }}}">
    <!-- Header Controls -->
    <div class="sm:flex sm:items-center justify-between mb-8 bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
        <div class="relative max-w-md w-full sm:w-96 mb-4 sm:mb-0">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2.5 border-0 text-slate-900 bg-slate-50/50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 rounded-xl transition-all" placeholder="Buscar por nombre o SKU...">
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button @click="isEditing = true; $wire.createNew()" type="button" class="group inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-300">
                <svg class="mr-2 h-5 w-5 text-slate-300 group-hover:text-indigo-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nuevo Producto
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Detalles del Producto</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Costo (Base)</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Precio de Venta</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Inventario</th>
                        <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-500 font-bold shadow-inner">
                                        {{ substr($product->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-slate-900 tracking-tight">{{ $product->name }}</div>
                                        <div class="text-xs text-slate-500 flex items-center mt-0.5">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600 mr-2 border border-slate-200/60">
                                                {{ $product->category->name ?? 'Sin Categoría' }}
                                            </span>
                                            SKU: {{ $product->barcode ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4">
                                <div class="text-sm font-semibold text-slate-700 tracking-tight">
                                    ${{ number_format($product->purchase_price, 2) }}
                                </div>
                                @if($bcvRate > 0)
                                    <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                        Bs. {{ number_format($product->purchase_price * $bcvRate, 2, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4">
                                <div class="inline-flex flex-col">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-emerald-50 text-sm font-bold text-emerald-700 font-mono tracking-tight ring-1 ring-inset ring-emerald-600/20">
                                        ${{ number_format($product->sale_price, 2) }}
                                    </span>
                                    @if($bcvRate > 0)
                                        <span class="text-[11px] font-bold text-emerald-600/70 mt-1 ml-1">
                                            Bs. {{ number_format($product->sale_price * $bcvRate, 2, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4">
                                @if($product->stock <= $product->min_stock)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></div>
                                        {{ $product->stock }} / {{ $product->min_stock }} min
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $product->stock }} en stock
                                    </span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                <button @click="isEditing = true; $wire.edit({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors mr-2 inline-flex">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button onclick="confirm('¿Estás seguro de que deseas eliminar este producto?') || event.stopImmediatePropagation()" wire:click="delete({{ $product->id }})" class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors inline-flex">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900">No hay productos</h3>
                                    <p class="mt-1 text-sm text-slate-500">Agrega tu primer producto al inventario.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Slide-over Form (Alpine.js integration) -->
    <div x-show="isEditing" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="isEditing" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                    <div x-show="isEditing" 
                        x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" 
                        x-transition:enter-start="translate-x-full" 
                        x-transition:enter-end="translate-x-0" 
                        x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" 
                        x-transition:leave-start="translate-x-0" 
                        x-transition:leave-end="translate-x-full" 
                        class="pointer-events-auto w-screen max-w-md">
                        
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl">
                            <div class="bg-slate-900 px-4 py-6 sm:px-6 relative overflow-hidden">
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl"></div>
                                <div class="flex items-center justify-between relative z-10">
                                    <h2 class="text-xl font-bold text-white tracking-tight" id="slide-over-title">
                                        {{ $productId ? 'Editar Producto' : 'Nuevo Producto' }}
                                    </h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button @click="isEditing = false" type="button" class="rounded-lg text-slate-400 hover:text-white hover:bg-white/10 p-1 transition-colors focus:outline-none focus:ring-2 focus:ring-white">
                                            <span class="sr-only">Cerrar panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="relative flex-1 px-4 py-6 sm:px-6" >
                                <form wire:submit.prevent="save" class="space-y-6">
                                    <!-- Información Básica -->
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Información General</h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold leading-6 text-slate-900">Nombre del Producto</label>
                                                <div class="mt-2">
                                                    <input type="text" wire:model="name" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                </div>
                                                @error('name') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-semibold leading-6 text-slate-900">SKU / Código</label>
                                                    <div class="mt-2">
                                                        <input type="text" wire:model="barcode" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                    </div>
                                                    @error('barcode') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold leading-6 text-slate-900">Categoría</label>
                                                    <div class="mt-2">
                                                        <select wire:model="category_id" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                            <option value="">Seleccione...</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('category_id') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bimonetary Prices -->
                                    <div class="pt-2">
                                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Estructura de Precios</h3>
                                            @if($bcvRate > 0)
                                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-[10px] font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                                Tasa Activa: Bs. {{ number_format($bcvRate, 2, ',', '.') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/60 space-y-4">
                                            <!-- Costo -->
                                            <div>
                                                <label class="block text-sm font-semibold leading-6 text-slate-900">Costo Base (USD)</label>
                                                <div class="mt-2 relative">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <span class="text-slate-500 sm:text-sm font-bold">$</span>
                                                    </div>
                                                    <input type="number" step="0.01" wire:model="purchase_price"  class="block w-full rounded-xl border-0 py-2.5 pl-8 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-mono">
                                                </div>
                                                <div class="mt-1.5 flex justify-end" x-show="$wire.purchase_price > 0 && bcvRate > 0">
                                                    <span class="text-[11px] font-medium text-slate-500">Ref. Costo VES: <span class="font-bold text-slate-700">Bs. <span x-text="((parseFloat($wire.purchase_price) || 0) * bcvRate).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span></span>
                                                </div>
                                                @error('purchase_price') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                            </div>

                                            <!-- Precio Venta -->
                                            <div>
                                                <label class="block text-sm font-semibold leading-6 text-emerald-700">Precio de Venta (USD)</label>
                                                <div class="mt-2 relative">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <span class="text-emerald-600 sm:text-sm font-bold">$</span>
                                                    </div>
                                                    <input type="number" step="0.01" wire:model="sale_price"  class="block w-full rounded-xl border-0 bg-emerald-50/50 py-2.5 pl-8 text-emerald-900 ring-1 ring-inset ring-emerald-200 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 font-mono font-bold">
                                                </div>
                                                <div class="mt-1.5 flex justify-end" x-show="$wire.sale_price > 0 && bcvRate > 0">
                                                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                                        PVP Bolívares: Bs. <span x-text="((parseFloat($wire.sale_price) || 0) * bcvRate).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                                    </span>
                                                </div>
                                                @error('sale_price') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Inventario -->
                                    <div class="pt-2">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Niveles de Inventario</h3>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold leading-6 text-slate-900">Stock Actual</label>
                                                <div class="mt-2">
                                                    <input type="number" wire:model="stock" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                </div>
                                                @error('stock') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold leading-6 text-slate-900">Stock Mínimo (Alerta)</label>
                                                <div class="mt-2">
                                                    <input type="number" wire:model="min_stock" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                </div>
                                                @error('min_stock') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-8 flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-100">
                                        <button type="button" @click="isEditing = false" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                                            {{ $productId ? 'Guardar Cambios' : 'Crear Producto' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>