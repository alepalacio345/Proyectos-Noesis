<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Cuentas Nacionales (Venezuela)</h3>
            <p class="text-sm text-slate-500">Configura los datos bancarios que se mostrarán en la caja (POS).</p>
        </div>
        <button wire:click="create" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Agregar Cuenta
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 rounded-xl bg-emerald-50 p-4 border border-emerald-100">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Grid of Accounts --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($accounts as $account)
            <div class="relative bg-white rounded-2xl border {{ $account->is_active ? 'border-slate-200 shadow-sm' : 'border-slate-200/50 bg-slate-50/50 opacity-75' }} overflow-hidden group">
                {{-- Method Badge --}}
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                        {{ $account->method === 'pago_movil' ? 'bg-teal-50 text-teal-700 ring-teal-600/20' : '' }}
                        {{ $account->method === 'transfer' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : '' }}
                        {{ $account->method === 'pos_terminal' ? 'bg-purple-50 text-purple-700 ring-purple-600/20' : '' }}
                    ">
                        {{ $account->method === 'pago_movil' ? 'Pago Móvil' : ($account->method === 'transfer' ? 'Transferencia' : 'Punto de Venta') }}
                    </span>
                </div>

                <div class="p-5">
                    <h4 class="text-base font-bold text-slate-900 mb-1 pr-24 truncate">{{ $account->label }}</h4>
                    <p class="text-sm text-slate-500 font-medium mb-4">{{ $account->bank ?? 'Sin banco' }}</p>

                    <div class="space-y-2 text-sm text-slate-600">
                        @if($account->method === 'pago_movil')
                            <div class="flex justify-between"><span class="text-slate-400">Teléfono:</span> <span class="font-mono font-medium">{{ $account->phone }}</span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Cédula/RIF:</span> <span class="font-mono font-medium">{{ $account->id_number }}</span></div>
                        @elseif($account->method === 'transfer')
                            <div class="flex justify-between"><span class="text-slate-400">Cuenta:</span> <span class="font-mono font-medium truncate ml-2">{{ $account->account_number }}</span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Titular:</span> <span class="font-medium truncate ml-2">{{ $account->holder_name }}</span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Identificación:</span> <span class="font-mono font-medium">{{ $account->id_number }}</span></div>
                        @endif
                    </div>
                </div>

                <div class="bg-slate-50 border-t border-slate-100 px-5 py-3 flex items-center justify-between">
                    <button wire:click="toggleActive({{ $account->id }})" class="flex items-center gap-2 text-sm font-semibold {{ $account->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                        <div class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $account->is_active ? 'bg-emerald-500' : 'bg-slate-200' }}">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform {{ $account->is_active ? 'translate-x-4' : 'translate-x-1' }}"></span>
                        </div>
                        {{ $account->is_active ? 'Activa' : 'Inactiva' }}
                    </button>
                    
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $account->id }})" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                        </button>
                        <button wire:click="delete({{ $account->id }})" wire:confirm="¿Seguro que deseas eliminar esta cuenta?" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900">No hay cuentas configuradas</h3>
                <p class="mt-1 text-sm text-slate-500">Agrega las cuentas de tu negocio para mostrarlas en el Punto de Venta.</p>
                <button wire:click="create" class="mt-4 inline-flex items-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 shadow-sm">
                    Agregar Cuenta
                </button>
            </div>
        @endforelse
    </div>

    {{-- Slide-over Modal --}}
    @if ($showModal)
    <div class="relative z-50">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col bg-white shadow-2xl overflow-y-auto">
                            <div class="bg-slate-50 px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                                <h2 class="text-lg font-bold text-slate-900">{{ $editId ? 'Editar Cuenta' : 'Nueva Cuenta' }}</h2>
                                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <div class="flex-1 px-6 py-6 space-y-5">
                                {{-- Tipo de Método --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">Método de Pago</label>
                                    <select wire:model.live="method" class="block w-full rounded-xl border-0 py-2.5 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                        <option value="pago_movil">Pago Móvil</option>
                                        <option value="transfer">Transferencia Bancaria</option>
                                        <option value="pos_terminal">Punto de Venta (Terminal físico)</option>
                                    </select>
                                </div>

                                {{-- Etiqueta / Nombre --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nombre Identificador</label>
                                    <input type="text" wire:model="label" placeholder="Ej: Pago Móvil Banesco" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                    @error('label') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                                </div>

                                {{-- Banco --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">Banco</label>
                                    <input type="text" wire:model="bank" placeholder="Ej: Banesco, Mercantil, BDV..." class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                    @error('bank') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                                </div>

                                @if ($method === 'pago_movil')
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-900 mb-2">Teléfono</label>
                                        <input type="text" wire:model="phone" placeholder="Ej: 0414-1234567" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-900 mb-2">Cédula o RIF</label>
                                        <input type="text" wire:model="id_number" placeholder="Ej: V-12345678" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm font-mono">
                                    </div>
                                @elseif ($method === 'transfer')
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-900 mb-2">Número de Cuenta</label>
                                        <input type="text" wire:model="account_number" placeholder="20 dígitos" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm font-mono">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-900 mb-2">Tipo de Cuenta</label>
                                            <select wire:model="account_type" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                                <option value="corriente">Corriente</option>
                                                <option value="ahorro">Ahorro</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-900 mb-2">Cédula o RIF</label>
                                            <input type="text" wire:model="id_number" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm font-mono">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-900 mb-2">Nombre del Titular</label>
                                        <input type="text" wire:model="holder_name" class="block w-full rounded-xl border-0 py-2.5 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                                    </div>
                                @endif
                            </div>

                            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <button wire:click="$set('showModal', false)" class="text-sm font-semibold text-slate-600 hover:text-slate-900 px-3 py-2">Cancelar</button>
                                <button wire:click="save" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar Cuenta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>