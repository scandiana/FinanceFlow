@props(['label', 'value', 'variant' => 'default', 'href' => null, 'footer' => null])
@php
    $tag = $href ? 'a' : 'div';
@endphp
<{{ $tag }} @if($href) href="{{ $href }}" class="card link-card metric-card" @else class="card metric-card" @endif>
    <div class="metric-row">
        <div>
            <p class="label">{{ $label }}</p>
            <p class="value {{ in_array($variant, ['success','danger']) ? $variant : '' }}">{{ $value }}</p>
            @if($footer)<div style="margin-top:0.75rem;font-size:0.875rem;">{!! $footer !!}</div>@endif
        </div>
        @if(isset($icon))
            <div class="metric-icon {{ $icon }}">{{ $slot }}</div>
        @endif
    </div>
</{{ $tag }}>
