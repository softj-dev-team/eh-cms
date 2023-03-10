{{-- $options['value'] --}}
<style>
    .popular-search {
        margin-left: 0px;
    }

    .popular-search a {
        padding: 5px 1.33333em;
        max-height: 30px;
        min-height: 30px;
    }
    .categories_parents {
        padding: 5px 1.33333em;
        max-height: 30px;
        min-height: 30px;
        margin-right: 15px;
        margin-bottom: 15px;


        background: #344d5a;
        border-color: #2d8ec5;
        color: #ffffff;
    }
</style>
<?php $categories[1] = 0; $categories[2] = 0?>
<div class="form-group">

    <label for="categiries" class="control-label" aria-required="true" style="margin-bottom: 20px;">{{__('life.flea_market.categories')}}</label>

    <div style="margin-left: 20px">
    <div class="form-group">
        <label for="categiries" class="control-label" aria-required="true" style="margin-bottom: 20px;">{{__('life.flea_market.categories')}} 1 :</label> <br>
        <?php $count = 0; $type  = 0  ; if($options['value'] != null){$type=1;}?>
        @foreach ($options['data-value'] as $key => $item)
            @if ($item->parent_id == 1)
            <label class="control-label popular-search categories-1 ">
                <a href="javascript:{}" title="{{$item->name}}" class="categories_parents <?php
                        switch ($type) {
                            case 0:
                                if($count == 0){
                                    echo 'category-active' ;
                                    $categories[1]= $item->id;
                                    $count = 1;
                                }
                                break;

                            case 1:
                                if(in_array($item->id, $options['value'])){
                                    echo 'category-active' ;
                                    $categories[1]= $item->id;

                                };
                                break;
                        }
                     ?> " data-value="{{$item->id}}" id="parent{{ $item->id ?? '0' }}">{{$item->name}}</a>

            </label>
            @endif
        @endforeach
        <input type="hidden" name="categories[1]" id="categories1" value="{{$categories[1]}}">

    </div>
    <div class="form-group">
        <label for="categiries" class="control-label" aria-required="true" style="margin-bottom: 20px;">{{__('life.flea_market.categories')}} 2 :</label> <br>

        <?php $count = 0; $type=0 ; if($options['value'] != null){$type = 1;}?>

        @foreach ($options['data-value'] as $key => $item)

        @if ($item->parent_id == 2)
            <label class="control-label popular-search categories-2  ">
                <a href="javascript:{}" title="{{$item->name}}" class="categories_parents <?php
                       switch ($type) {
                            case 0:
                                if($count == 0){
                                    echo 'category-active' ;
                                    $categories[2]= $item->id;
                                    $count = 1;
                                }
                                break;

                            case 1:
                                if(in_array($item->id, $options['value'])){
                                    echo 'category-active' ;
                                    $categories[2]= $item->id;


                                };
                                break;
                        }
                      ?>" data-value="{{$item->id}}" id="child{{ $item->id ?? '0' }}">{{$item->name}}</a>
            </label>
        @endif
        @endforeach
    </div>
    </div>
</div>

<input type="hidden" name="categories[2]" id="categories2" value="{{$categories[2]}}">
<script>
    $(function () {

        $(".categories-1 a").click(function() {
            $(".categories-1 a ").removeClass('category-active');
            $(this).addClass('category-active');
            $("#categories1").val( $(this).attr('data-value'));

        });
        $(".categories-2 a").click(function() {
            $(" .categories-2 a ").removeClass('category-active');
            $(this).addClass('category-active');
            $("#categories2").val( $(this).attr('data-value'));
        });
});
</script>
<style>
  .categories_parents{
    background: #FFF1F6;
    color: #0D0A0A;
  }
  .categories_parents.category-active{
    background: #5d5c5c!important;
    color: #ffffff;
  }

</style>
