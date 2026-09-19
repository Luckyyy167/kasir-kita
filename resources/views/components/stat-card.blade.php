@props([
    'title' => 'Metrik',
    'value' => '0',
    'trend' => null,
    'trendUp' => true,
    'subtitle' => 'vs kemarin',
    'iconBg' => 'bg-cream-100 text-coffee-800'
])

<div class="bg-white rounded-3xl p-5 border border-[#EAE2D5] shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
    <div class="flex items-center justify-between gap-3">
        <span class="text-xs font-bold text-coffee-600 uppercase tracking-wider">{{ $title }}</span>
        <div class="w-10 h-10 rounded-2xl {{ $iconBg }} flex items-center justify-center shrink-0 shadow-inner">
            {{ $slot }}
        </div>
    </div>

    <div class="mt-4">
        <h3 class="text-2xl font-black text-coffee-950 tracking-tight leading-tight">{{ $value }}</h3>
        
        @if($trend !== null)
            <div class="flex items-center gap-1.5 mt-2">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold {{ $trendUp ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($trendUp)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path>
                        @endif
                    </svg>
                    <span>{{ $trend > 0 ? '+'.$trend : $trend }}%</span>
                </span>
                <span class="text-[11px] text-coffee-500 font-medium">{{ $subtitle }}</span>
            </div>
        @endif
    </div>
</div>
