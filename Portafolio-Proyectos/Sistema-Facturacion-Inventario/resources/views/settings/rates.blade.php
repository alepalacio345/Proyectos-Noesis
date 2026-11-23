<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 leading-tight tracking-tight">
            Configuración
        </h2>
    </x-slot>

    <div class="py-6">
        <x-settings-nav />
        <livewire:settings.exchange-rate-manager />
    </div>
</x-app-layout>