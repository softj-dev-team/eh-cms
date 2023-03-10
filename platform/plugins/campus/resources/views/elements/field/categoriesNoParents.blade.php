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

    .popular-search a.active,
    .popular-search a:hover {
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
</style>
<?php $categories[1] = 0; $categories[2] = 0?>
@if ( count( $options['data-value'] ) > 0 )
<div class="form-group">

    <label for="categiries" class="control-label" aria-required="true" style="margin-bottom: 20px;">{{ __('life.flea_market.classification') }}</label>

    <div style="margin-left: 20px">
        <div class="form-group">
            <?php $count = 0; $type  = 0  ; if($options['value'] != null){$type=1;}?>


            @foreach ($options['data-value'] as $key => $item)
                <label class="control-label popular-search categories" style="margin-bottom: 15px;">
                    <a href="javascript:{}" title="{{$item->name}}" class="categories_parents <?php
                            switch ($type) {
                                case 0:
                                    if($count == 0){
                                        echo 'active' ;
                                        $categories= $item->id;
                                        $count = 1;
                                    }
                                    break;

                                case 1:
                                    if($item->id == $options['value']){
                                        echo 'active' ;
                                        $categories= $item->id;

                                    };
                                    break;
                            }
                        ?> " data-value="{{$item->id}}" id="parent{{ $item->id ?? '0' }}">{{$item->name}}</a>
                            <style>#parent{{ $item->id ?? '0' }} {background-color: #64C3FF;color: #FFFFFF;}</style>

                </label>
            @endforeach


            <input type="hidden" name="categories" id="categories" value="{{$categories}}">
        </div>
    </div>
</div>
@endif
<script>
    $(function () {

        $(".categories a").click(function() {
            $(".categories a ").removeClass('active');
            $(this).addClass('active');
            $("#categories").val( $(this).attr('data-value'));

        });
});
</script>
