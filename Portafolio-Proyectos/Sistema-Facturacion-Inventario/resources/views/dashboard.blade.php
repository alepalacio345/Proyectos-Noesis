<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 leading-tight tracking-tight">
            Panel Principal
        </h2>
    </x-slot>

    <div class="flex flex-col space-y-6">
        
        <!-- Dashboard Metrics & Exchange Rate Widget -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Exchange Rate Widget -->
            <livewire:settings.exchange-rate-widget />

            <!-- Ventas Hoy -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 flex items-center gap-4 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="h-14 w-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 ring-1 ring-inset ring-emerald-500/20 relative z-10">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Ventas Hoy</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">$0.00</h3>
                </div>
            </div>

            <!-- Productos -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 flex items-center gap-4 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 ring-1 ring-inset ring-blue-500/20 relative z-10">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Productos Totales</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ \App\Models\Product::count() ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Gráficas y Estadísticas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Gráfica de Ventas Semanales (Estructura Mockup) -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Ventas de la Semana</h3>
                    <select class="text-sm bg-slate-50 border-none rounded-lg text-slate-600 focus:ring-0 cursor-pointer font-medium py-1.5 pl-3 pr-8">
                        <option>Últimos 7 días</option>
                        <option>Este mes</option>
                    </select>
                </div>
                
                <div class="h-64 flex items-end justify-between gap-2 px-2">
                    <!-- Barras Simuladas -->
                    <div class="w-full bg-indigo-100 rounded-t-lg h-[40%] relative group transition-all hover:bg-indigo-200 cursor-pointer"><span class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold text-slate-700 bg-white shadow-md px-2 py-1 rounded transition-opacity">Lun</span></div>
                    <div class="w-full bg-indigo-100 rounded-t-lg h-[65%] relative group transition-all hover:bg-indigo-200 cursor-pointer"><span class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold text-slate-700 bg-white shadow-md px-2 py-1 rounded transition-opacity">Mar</span></div>
                    <div class="w-full bg-indigo-500 rounded-t-lg h-[90%] relative group transition-all hover:bg-indigo-600 cursor-pointer shadow-lg shadow-indigo-500/20"><span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold text-slate-700 bg-white shadow-md px-2 py-1 rounded transition-opacity">Hoy</span></div>
                    <div class="w-full bg-indigo-100 rounded-t-lg h-[50%] relative group transition-all hover:bg-indigo-200 cursor-pointer"><span class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold text-slate-700 bg-white shadow-md px-2 py-1 rounded transition-opacity">Jue</span></div>
                    <div class="w-full bg-indigo-100 rounded-t-lg h-[30%] relative group transition-all hover:bg-indigo-200 cursor-pointer"><span class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold text-slate-700 bg-white shadow-md px-2 py-1 rounded transition-opacity">Vie</span></div>
                    <div class="w-full bg-indigo-100 rounded-t-lg h-[75%] relative group transition-all hover:bg-indigo-200 cursor-pointer"><span class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold text-slate-700 bg-white shadow-md px-2 py-1 rounded transition-opacity">Sáb</span></div>
                    <div class="w-full bg-indigo-100 rounded-t-lg h-[20%] relative group transition-all hover:bg-indigo-200 cursor-pointer"><span class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-xs font-bold text-slate-700 bg-white shadow-md px-2 py-1 rounded transition-opacity">Dom</span></div>
                </div>
                <div class="flex justify-between items-center text-xs font-semibold text-slate-400 mt-3 px-2 border-t border-slate-100 pt-3">
                    <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
                </div>
            </div>

            <!-- Productos Populares / Bajo Stock -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 flex flex-col">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Alertas de Inventario (Bajo Stock)</h3>
                <div class="flex-1 flex flex-col justify-center items-center text-center p-8 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center text-slate-300 mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="text-slate-700 font-bold mb-1">¡Inventario Saludable!</h4>
                    <p class="text-sm text-slate-500">Actualmente no tienes productos por debajo del límite de stock mínimo.</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>