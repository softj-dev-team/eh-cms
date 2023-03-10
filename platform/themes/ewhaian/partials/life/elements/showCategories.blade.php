<span class="alert" id="Classification{{$flareCategories->id ?? '0' }}" style="">{{$flareCategories->name ?? "No name"}}</span>
@php
    $style = request()->style == 2 ? '100%' : 'auto';
@endphp
<style>
#Classification{{ $flareCategories->id ?? '0' }}  {
    background-color: #{{$flareCategories->background ?? '000000'}};
    color: #{{$flareCategories->color ?? 'FFFFFF' }};
    width: {!! $style !!};
    white-space: nowrap;
}
</style>
