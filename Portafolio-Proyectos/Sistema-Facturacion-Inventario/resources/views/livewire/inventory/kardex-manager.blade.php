<div>
    {{-- Header & Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Historial Global de Movimientos</h3>
            <p class="text-sm text-slate-500">Registro auditable de todas las entradas y salidas de inventario.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="relative max-w-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar producto o motivo..." class="block w-full rounded-xl border-0 py-2 pl-10 pr-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
            </div>
            
            <select wire:model.live="typeFilter" class="block rounded-xl border-0 py-2 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                <option value="">Todos los tipos</option>
                <option value="in">Entradas (+)</option>
                <option value="out">Salidas (-)</option>
            </select>
        </div>
    </div>

    {{-- Movements Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Fecha / Hora</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Producto</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Motivo</th>
                        <th scope="col" class="px-3 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Movimiento</th>
                        <th scope="col" class="px-3 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Stock Final</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($movements as $mov)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-900">
                                <div class="font-medium">{{ $mov->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $mov->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @if($mov->product)
                                    <div class="font-bold text-slate-900">{{ $mov->product->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $mov->product->barcode ?? 'Sin código' }}</div>
                                @else
                                    <span class="text-slate-400 italic">Producto eliminado</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-slate-700">
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
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-center font-bold text-slate-900">
                                {{ $mov->new_stock }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                <h3 class="mt-4 text-sm font-semibold text-slate-900">No hay movimientos</h3>
                                <p class="mt-1 text-sm text-slate-500">Aún no se han registrado movimientos de inventario.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($movements->hasPages())
            <div class="bg-slate-50 border-t border-slate-200 px-6 py-4">
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>