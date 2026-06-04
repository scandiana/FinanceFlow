@props(['title', 'subtitle' => null, 'amount', 'href' => '#', 'badge' => null])
<a href="{{ $href }}" class="card link-card" style="padding:1rem;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
            <p style="font-weight:500;font-size:0.875rem;">{{ $title }}</p>
            @if($subtitle)<p class="text-muted" style="font-size:0.75rem;margin-top:0.25rem;">{{ $subtitle }}</p>@endif
        </div>
        @if($badge)<span class="badge {{ $badge }}">{{ $slot }}</span>@endif
    </div>
    <p style="font-size:1.125rem;font-weight:600;margin-top:0.75rem;">{{ $amount }}</p>
</a>
