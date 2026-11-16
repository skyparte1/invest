@props(['name'])
<svg {{ $attributes }} width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('system')
            <rect x="3" y="4" width="18" height="13" rx="2"/><path d="M9 21h6m-3-4v4"/>
            @break
        @case('light')
            <circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M5 5l1.5 1.5m11 11L19 19M5 19l1.5-1.5m11-11L19 5"/>
            @break
        @case('dark')
            <path d="M20.5 14.4A9 9 0 0 1 9.6 3.5a9 9 0 1 0 10.9 10.9Z"/>
            @break
        @case('learn')
            <path d="M3 5c3-.9 6-.5 9 1.2 3-1.7 6-2.1 9-1.2v14c-3-.9-6-.5-9 1-3-1.5-6-1.9-9-1zM12 6.2V20"/>
            @break
        @case('investments')
            <rect x="3" y="5" width="14" height="11" rx="2"/><path d="M7 19h12a2 2 0 0 0 2-2V9"/><circle cx="10" cy="10.5" r="2"/>
            @break
        @case('simulator')
            <path d="M3 3v17h18M7 14l4-5 4 3 5-7"/>
            @break
        @case('planning')
            <circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>
            @break
    @endswitch
</svg>
