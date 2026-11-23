<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 leading-tight tracking-tight">
            Kardex
        </h2>
    </x-slot>

    <div class="py-6">
        <livewire:inventory.kardex-manager />
    </div>
</x-app-layout>