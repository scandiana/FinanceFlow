@props(['items' => []])
<nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('dashboard') }}">Início</a>
    @foreach($items as $item)
        <span class="sep">/</span>
        @if(!empty($item['url']))
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        @else
            <span>{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
