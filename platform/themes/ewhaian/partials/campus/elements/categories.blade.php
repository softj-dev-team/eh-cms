<style>
  .popular-search a.active,
    .popular-search a:hover {
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
</style>
<div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2" style="margin-bottom: -10px ">
    <p class="text-bold mr-4">{{__('campus.classification')}} <span class="required">*</span></p>
    <div class="d-flex flex-wrap flex-grow-1">
        <div class="popular-search">
            <ul class="popular__list">
                <?php $count = 0; $type=0 ; if($selectedCategories != null){$type = 1;}?>
                @foreach ($categories as $item)
                @if($item->parent_id == ($parent ?? null))
                <li class="popular__item">
                    <a class="alert <?php
                      switch ($type) {
                            case 0:
                                if($count == 0){
                                    echo 'active' ;
                                    $categories[2]= $item->id;
                                    $count = 1;
                                }
                                break;

                            case 1:
                                if($item->id == $selectedCategories->id){
                                    echo 'active' ;
                                    $categories[2]= $item->id;
                                };
                                break;
                        }
                    ?>" id="child{{ $item->id ?? '0' }}" href="javascript:{}" title="{{$item->name}}" data-value="{{$item->id}}">{{$item->name}}</a>
                    <style>#child{{ $item->id ?? '0' }} {background-color: #{{$item->background ?? '000000'}};color: #{{$item->color ?? 'FFFFFF' }};}</style>
                </li>
                @endif
                @endforeach
            </ul>
            <input type="hidden" name="categories[2]" id="categories2" value="{{$categories[2]}}">
        </div>
    </div>
</div>
