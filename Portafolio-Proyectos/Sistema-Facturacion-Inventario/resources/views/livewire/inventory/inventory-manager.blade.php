<div x-data="{ bcvRate: {{ $bcvRate }} }">

    {{-- Flash message --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 flex items-center gap-2 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 border border-emerald-100">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- ── Tabs ──────────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-6">
        <div class="inline-flex bg-slate-200/50 backdrop-blur-md p-1.5 rounded-2xl shadow-inner border border-white/60">
            <button wire:click="setTab('products')"
                    class="relative flex items-center px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
                           {{ $activeTab === 'products' ? 'bg-white text-indigo-900 shadow-sm ring-1 ring-slate-900/5' : 'text-slate-500 hover:text-slate-800' }}">
                Productos
            </button>
            <button wire:click="setTab('categories')"
                    class="relative flex items-center px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 ml-1
                           {{ $activeTab === 'categories' ? 'bg-white text-indigo-900 shadow-sm ring-1 ring-slate-900/5' : 'text-slate-500 hover:text-slate-800' }}">
                Categorías
            </button>
        </div>

        {{-- New button for each tab --}}
        @if ($activeTab === 'products')
            <button wire:click="createProduct"
                    class="group inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 transition-all duration-200">
                <svg class="mr-2 h-5 w-5 text-slate-300 group-hover:text-indigo-200" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nuevo Producto
            </button>
        @else
            <button wire:click="createCategory"
                    class="group inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 transition-all duration-200">
                <svg class="mr-2 h-5 w-5 text-slate-300 group-hover:text-indigo-200" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nueva Categoría
            </button>
        @endif
    </div>

    {{-- Search --}}
    <div class="mb-6 relative max-w-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
        </div>
        <input wire:model.live.debounce.300ms="search" type="text"
               class="block w-full pl-10 pr-3 py-2.5 border-0 text-slate-900 bg-white ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm rounded-xl transition-all shadow-sm"
               placeholder="{{ $activeTab === 'products' ? 'Buscar producto o SKU...' : 'Buscar categoría...' }}">
    </div>

    {{-- ════════════════════ PRODUCTS TAB ════════════════════ --}}
    @if ($activeTab === 'products')
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-4 pl-6 pr-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Producto</th>
                        <th class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Costo (USD)</th>
                        <th class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Precio de Venta</th>
                        <th class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Inventario</th>
                        <th class="py-4 pl-3 pr-6 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($products as $product)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3">
                            <div class="flex items-center">
                                <div class="h-10 w-10 shrink-0 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold">
                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600 mr-1">
                                            {{ $product->category->name ?? 'Sin Categoría' }}
                                        </span>
                                        SKU: {{ $product->barcode ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="text-sm font-semibold text-slate-700">${{ number_format($product->purchase_price, 2) }}</div>
                            @if ($bcvRate > 0)
                            <div x-text="'Bs. ' + ({{ $product->purchase_price }} * bcvRate).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2})"
                                 class="text-[11px] text-slate-400 font-medium mt-0.5"></div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg bg-emerald-50 text-sm font-bold text-emerald-700 font-mono ring-1 ring-inset ring-emerald-600/20">
                                ${{ number_format($product->sale_price, 2) }}
                            </span>
                            @if ($bcvRate > 0)
                            <div x-text="'Bs. ' + ({{ $product->sale_price }} * bcvRate).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2})"
                                 class="text-[11px] font-bold text-emerald-600/70 mt-1"></div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @if ($product->stock <= $product->min_stock)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></div>
                                    {{ $product->stock }} / {{ $product->min_stock }} mín
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $product->stock }} en stock
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right">
                            <button wire:click="showKardex({{ $product->id }})"
        class="text-blue-600 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors mr-2 inline-flex" title="Ver Historial (Kardex)">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
    </svg>
</button>
                            <button wire:click="editProduct({{ $product->id }})"
                                    class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors mr-2 inline-flex">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button wire:click="deleteProduct({{ $product->id }})"
                                    onclick="return confirm('¿Eliminar este producto?')"
                                    class="text-rose-600 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors inline-flex">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <svg class="mx-auto w-10 h-10 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <p class="text-sm font-semibold text-slate-500">No hay productos. Crea el primero.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $products->links() }}</div>
        @endif
    </div>
    @endif

    {{-- ════════════════════ CATEGORIES TAB ════════════════════ --}}
    @if ($activeTab === 'categories')
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-4 pl-6 pr-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-3 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Productos</th>
                        <th class="py-4 pl-3 pr-6 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-violet-100 to-indigo-100 flex items-center justify-center text-violet-600 font-bold text-sm">
                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-bold text-slate-900">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $category->description ?? '—' }}</td>
                        <td class="px-3 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                {{ $category->products_count }} productos
                            </span>
                        </td>
                        <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right">
                            <button wire:click="editCategory({{ $category->id }})"
                                    class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors mr-2 inline-flex">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button wire:click="deleteCategory({{ $category->id }})"
                                    onclick="return confirm('¿Eliminar esta categoría?')"
                                    class="text-rose-600 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors inline-flex">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <svg class="mx-auto w-10 h-10 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                            <p class="text-sm font-semibold text-slate-500">No hay categorías. Crea la primera.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $categories->links() }}</div>
        @endif
    </div>
    @endif

    {{-- ════════════════════ MODAL (Slide-over) ════════════════════ --}}
    @if ($showModal)
    <div class="relative z-50">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

        {{-- Panel --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-auto bg-white shadow-2xl">

                            {{-- Header --}}
                            <div class="bg-slate-900 px-6 py-6 relative overflow-hidden">
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl"></div>
                                <div class="flex items-center justify-between relative z-10">
                                    <h2 class="text-xl font-bold text-white">
                                        @if ($activeTab === 'products')
                                            {{ $productId ? 'Editar Producto' : 'Nuevo Producto' }}
                                        @else
                                            {{ $categoryId ? 'Editar Categoría' : 'Nueva Categoría' }}
                                        @endif
                                    </h2>
                                    <button wire:click="closeModal" class="rounded-lg text-slate-400 hover:text-white hover:bg-white/10 p-1.5 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="flex-1 px-6 py-6">

                                @if ($activeTab === 'products')
                                {{-- ── Product Form ── --}}
                                <form wire:submit.prevent="saveProduct" class="space-y-5">

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-800 mb-1.5">Nombre del Producto</label>
                                        <input type="text" wire:model="name" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm shadow-sm">
                                        @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                    </div>

                                                                        <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 mb-1.5">Stock Actual</label>
                                            <input type="number" wire:model="stock" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm shadow-sm">
                                            @error('stock') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 mb-1.5">Stock Mínimo</label>
                                            <input type="number" wire:model="min_stock" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm shadow-sm">
                                            @error('min_stock') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="p-4 bg-orange-50 border border-orange-100 rounded-xl space-y-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:model.live="is_perishable" id="is_perishable" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            <label for="is_perishable" class="ml-2 block text-sm font-semibold text-slate-800">
                                                Producto Perecedero (Controlar Caducidad)
                                            </label>
                                        </div>
                                        @if($is_perishable)
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 mb-1.5">Fecha de Vencimiento del Lote actual</label>
                                            <input type="date" wire:model="expires_at" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-orange-500 sm:text-sm shadow-sm">
                                            <p class="text-xs text-orange-600 mt-1">Obligatorio al sumar stock.</p>
                                            @error('expires_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                        </div>
                                        @endif
                                    </div>

                                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                                        <button type="button" wire:click="closeModal"
                                                class="flex-1 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="flex-1 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors shadow-sm">
                                            {{ $productId ? 'Guardar Cambios' : 'Crear Producto' }}
                                        </button>
                                    </div>
                                </form>

                                @else
                                {{-- ── Category Form ── --}}
                                <form wire:submit.prevent="saveCategory" class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-800 mb-1.5">Nombre de la Categoría</label>
                                        <input type="text" wire:model="categoryName" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm shadow-sm">
                                        @error('categoryName') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-800 mb-1.5">Descripción (opcional)</label>
                                        <textarea wire:model="categoryDescription" rows="3"
                                                  class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm shadow-sm resize-none"></textarea>
                                        @error('categoryDescription') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                                        <button type="button" wire:click="closeModal"
                                                class="flex-1 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="flex-1 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors shadow-sm">
                                            {{ $categoryId ? 'Guardar Cambios' : 'Crear Categoría' }}
                                        </button>
                                    </div>
                                </form>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Kardex Slide-over Modal --}}
    @if ($showKardexModal && $kardexProduct)
    <div class="relative z-50">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeKardex"></div>
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                    <div class="pointer-events-auto w-screen max-w-2xl">
                        <div class="flex h-full flex-col bg-white shadow-2xl overflow-y-auto">
                            <div class="bg-indigo-600 px-6 py-6 sm:px-8 border-b border-indigo-700 flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-bold text-white">Kardex (Historial de Movimientos)</h2>
                                    <p class="text-indigo-100 text-sm mt-1 font-medium">{{ $kardexProduct->name }} • Código: {{ $kardexProduct->barcode ?? 'N/A' }}</p>
                                </div>
                                <button wire:click="closeKardex" class="text-indigo-200 hover:text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <div class="flex-1 p-6 sm:p-8">
                                <div class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-100/80">
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Fecha / Hora</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Motivo</th>
                                                <th scope="col" class="px-3 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Cantidad</th>
                                                <th scope="col" class="px-3 py-3.5 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Stock Resultante</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 bg-white">
                                            @forelse($kardexMovements as $mov)
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-slate-900">
                                                        <div class="font-medium">{{ $mov->created_at->format('d/m/Y') }}</div>
                                                        <div class="text-xs text-slate-500">{{ $mov->created_at->format('h:i A') }}</div>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-medium">
                                                        {{ $mov->reason }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                                        @if($mov->type === 'in')
                                                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                                +{{ $mov->quantity }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center rounded-md bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                                                -{{ $mov->quantity }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-slate-900 text-right">
                                                        {{ $mov->new_stock }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-8 text-center text-sm text-slate-500">
                                                        No hay movimientos registrados para este producto.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</div>