<span class="alert" id="Classification{{$item->id ?? '0' }}" style="display: block;margin-left: 10px;">{{$item->name ?? "No name"}}</span>

<style>
#Classification{{ $item->id ?? '0' }}  {
    background-color: #{{$item->background ?? '000000'}};
    color: #{{$item->color ?? 'FFFFFF' }};

}
</style>
