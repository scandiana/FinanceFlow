@foreach(['data_inicio', 'data_fim', 'categoria_id', 'conta_id', 'tipo'] as $field)
    @if(request($field))
        <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
    @endif
@endforeach
