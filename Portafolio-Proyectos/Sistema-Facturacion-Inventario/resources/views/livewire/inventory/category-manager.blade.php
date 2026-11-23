<div class="space-y-6" x-data="{ isEditing: @entangle('isEditing') }">
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-sm text-slate-500">Agrupa tus productos para un mejor control del inventario.</p>
        </div>
        
        <button type="button" @click="isEditing = true; $wire.createNew()" class="group inline-flex items-center px-5 py-2.5 bg-slate-900 border border-transparent rounded-2xl font-semibold text-sm text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 shadow-[0_4px_14px_0_rgb(0,0,0,0.1)] hover:shadow-[0_4px_14px_0_rgb(79,70,229,0.39)] transition-all duration-300">
            <svg class="mr-2 -ml-1 h-5 w-5 text-slate-300 group-hover:text-indigo-100 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Nueva Categoría
        </button>
    </div>

    @if($errors->has('delete'))
        <div class="rounded-xl bg-rose-50 p-4 border border-rose-200 shadow-sm transition-all">
            <div class="flex">
                <div class="flex-shrink-0"><svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div>
                <div class="ml-3"><p class="text-sm font-medium text-rose-800">{{ $errors->first('delete') }}</p></div>
            </div>
        </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Nombre de Categoría</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Descripción</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Métricas</th>
                        <th scope="col" class="relative py-4 pl-3 pr-6 text-right text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-200 group">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3">
                            <div class="font-semibold text-slate-900 text-sm">{{ $category->name }}</div>
                        </td>
                        <td class="px-3 py-4 text-sm text-slate-500 max-w-xs truncate">
                            {{ $category->description ?? 'Sin descripción' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/10">
                                {{ $category->products()->count() }} Productos
                            </span>
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" @click="isEditing = true; $wire.edit({{ $category->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Editar">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button type="button" wire:click="delete({{ $category->id }})" wire:confirm="¿Seguro que deseas eliminar esta categoría?" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Eliminar">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 mb-4">
                                <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <h3 class="mt-2 text-sm font-semibold text-slate-900">Sin Categorías</h3>
                            <p class="mt-1 text-sm text-slate-500">Comienza a organizar tu inventario creando categorías.</p>
                            <div class="mt-6">
                                <button type="button" @click="isEditing = true; $wire.createNew()" class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    Nueva Categoría
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Centered Premium Modal -->
    <div x-show="isEditing" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div x-show="isEditing" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="isEditing = false; $wire.resetFields()"></div>

        <!-- Modal Panel -->
        <div x-show="isEditing" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-900 px-6 py-6 sm:px-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white" id="modal-title">
                        {{ $categoryId ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h3>
                    <button type="button" @click="isEditing = false; $wire.resetFields()" class="rounded-lg text-indigo-200 hover:text-white focus:outline-none transition-colors">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
            
            <form wire:submit.prevent="save">
                <div class="px-6 py-8 sm:px-8 space-y-6">
                    <div>
                        <label class="block text-sm font-semibold leading-6 text-slate-900">Nombre de la Categoría</label>
                        <div class="mt-2">
                            <input type="text" wire:model="name" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" placeholder="Ej. Bebidas">
                        </div>
                        @error('name') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold leading-6 text-slate-900">Descripción (Opcional)</label>
                        <div class="mt-2">
                            <textarea wire:model="description" rows="3" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all" placeholder="Detalles de la categoría..."></textarea>
                        </div>
                        @error('description') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="bg-slate-50/50 px-6 py-5 sm:flex sm:flex-row-reverse sm:px-8 border-t border-slate-100">
                    <button type="submit" @click="isEditing = false" class="inline-flex w-full justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 sm:ml-3 sm:w-auto transition-all duration-300">
                        Guardar Cambios
                    </button>
                    <button type="button" @click="isEditing = false; $wire.resetFields()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>