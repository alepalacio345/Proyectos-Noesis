<div>
    <div class="space-y-6">
        
        <!-- Tarjeta del Formulario (Arriba) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800">Registrar Nuevo Ajuste</h3>
                <p class="text-sm text-gray-500">Añade o descuenta inventario por motivos excepcionales.</p>
            </div>
            
            <div class="p-6">
                @if (session()->has('message'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl text-sm font-semibold border border-green-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <!-- Fila 1: Operación y Producto -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Tipo de Operación -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Operación</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" wire:click="$set('type', 'merma')" class="py-2.5 text-sm font-bold rounded-xl border-2 transition-all {{ $type === 'merma' ? 'bg-rose-50 border-rose-200 text-rose-700 shadow-sm' : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200 hover:bg-gray-50' }}">
                                    Salida (Merma)
                                </button>
                                <button type="button" wire:click="$set('type', 'devolucion')" class="py-2.5 text-sm font-bold rounded-xl border-2 transition-all {{ $type === 'devolucion' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 shadow-sm' : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200 hover:bg-gray-50' }}">
                                    Entrada (Devolución)
                                </button>
                            </div>
                        </div>

                        <!-- Producto -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Producto Afectado</label>
                            <select wire:model.live="product_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 transition-colors">
                                <option value="">-- Selecciona un producto --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (Stock actual: {{ $p->stock }})</option>
                                @endforeach
                            </select>
                            @error('product_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fila 2: Motivo y Cantidad -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Motivo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Motivo Específico</label>
                            <select wire:model="reason" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 transition-colors">
                                @if($type === 'merma')
                                    @foreach($mermaReasons as $r)
                                        <option value="{{ $r }}">{{ $r }}</option>
                                    @endforeach
                                @else
                                    @foreach($devolucionReasons as $r)
                                        <option value="{{ $r }}">{{ $r }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('reason') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cantidad -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                            <input type="number" wire:model="quantity" min="1" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 transition-colors">
                            @error('quantity') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fila 3: Vencimiento y Notas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Notas -->
                        <div class="{{ $is_perishable_selected && $type === 'devolucion' ? '' : 'md:col-span-2' }}">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones Adicionales (Opcional)</label>
                            <textarea wire:model="notes" rows="1" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors" placeholder="Ej. El empaque venía roto desde el camión..."></textarea>
                        </div>

                        <!-- Fecha Vencimiento (Dinámico) -->
                        @if($type === 'devolucion' && $is_perishable_selected)
                        <div class="bg-orange-50/50 border border-orange-100 rounded-xl p-4">
                            <label class="block text-sm font-semibold text-orange-800 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Fecha de Vencimiento del Reingreso
                            </label>
                            <input type="date" wire:model="expires_at" class="w-full rounded-lg border-orange-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm py-2">
                            @error('expires_at') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>

                    <!-- Botón -->
                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Registrar Ajuste en Kardex
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tarjeta de Historial (Abajo) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Últimos Movimientos de Ajuste</h3>
                    <p class="text-sm text-gray-500">Historial reciente de mermas y devoluciones</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Fecha / Hora</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Producto</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Motivo</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Impacto</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($recentMovements as $m)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ $m->created_at->format('d M, Y') }} <span class="text-gray-400 text-xs ml-1">{{ $m->created_at->format('h:ia') }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">{{ $m->product->name ?? 'Producto Eliminado' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    @if($m->type === 'out')
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-100 text-rose-600">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                        </span>
                                    @endif
                                    <span class="font-medium">{{ $m->reason }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-right {{ $m->type === 'out' ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $m->type === 'out' ? '-' : '+' }}{{ $m->quantity }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span class="mt-2 block text-sm font-medium text-gray-500">Aún no hay ajustes registrados en el Kardex.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>