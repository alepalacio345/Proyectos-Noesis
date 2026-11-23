<div class="mb-6 border-b border-slate-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <a href="{{ route('settings.rates') }}"
           class="{{ request()->routeIs('settings.rates') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}
                  whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors">
            Tasa de Cambio (BCV)
        </a>
        <a href="{{ route('settings.payment-accounts') }}"
           class="{{ request()->routeIs('settings.payment-accounts') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}
                  whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors">
            Cuentas Bancarias
        </a>
    </nav>
</div>