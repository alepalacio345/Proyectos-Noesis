<li>
    <a href="{{ route('dashboard') }}"
       class="group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
        </svg>
        Panel Principal
    </a>
</li>

<li>
    <a href="{{ route('pos.index') }}"
       class="group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('pos.*') ? 'bg-indigo-500 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('pos.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.39 3.089A1.5 1.5 0 015.45 2.648h13.101c.397 0 .778.158 1.06.439l1.8 2.247a3.004 3.004 0 01-.62 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
        </svg>
        Punto de Venta
    </a>
</li>

<li x-data="{ open: {{ request()->routeIs('inventory.*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
       class="w-full group flex items-center justify-between gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('inventory.*') ? 'bg-indigo-500/10 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <div class="flex items-center gap-x-3">
            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('inventory.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Inventario
        </div>
        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <ul x-show="open" 
        x-collapse 
        style="display: none;" 
        class="mt-1 space-y-1 pl-11 pr-2 pb-2">
        <li>
            <a href="{{ route('inventory.index') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium transition-all {{ request()->routeIs('inventory.index') ? 'text-white bg-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                Catálogo de Productos
            </a>
        </li>
        <li>
            <a href="{{ route('inventory.kardex') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium transition-all {{ request()->routeIs('inventory.kardex') ? 'text-white bg-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                Historial (Kardex)
            </a>
        </li>
        <li>
            <a href="{{ route('inventory.adjustments') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium transition-all {{ request()->routeIs('inventory.adjustments') ? 'text-white bg-indigo-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                Ajustes de Inventario
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="#"
       class="group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
        <svg class="h-6 w-6 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
        Clientes
    </a>
</li>

<li>
    <a href="#"
       class="group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
        <svg class="h-6 w-6 shrink-0 text-slate-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
        </svg>
        Reportes
    </a>
</li>