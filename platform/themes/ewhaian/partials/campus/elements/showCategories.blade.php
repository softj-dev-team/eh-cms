<span class="alert" id="Classification{{$flareCategories->id ?? '0' }}" style="display: block;margin-left: 10px;">{{$flareCategories->name ?? "No name"}}</span>

<style>
#Classification{{ $flareCategories->id ?? '0' }}  {
    background-color: #{{$flareCategories->background ?? '000000'}};
    color: #{{$flareCategories->color ?? 'FFFFFF' }};

}
</style>