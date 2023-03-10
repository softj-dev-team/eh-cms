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
    .popular-search a.active:hover {
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
    .popular-search a:focus,  .popular-search a:hover {
        background: #344d5a;
        border-color: #2d8ec5;
        color: #ffffff;
    }

</style>
<?php $categories[1] = 0; $categories[2] = 0?>
<div class="form-group">

    <label for="categiries" class="control-label" aria-required="true"
        style="margin-bottom: 20px;">{{$options['label']}}</label>

    <div style="margin-left: 20px">
        <div class="form-group">
            @foreach ($options['choices'] as $key => $item)

            <label class="control-label popular-search categories {{$options['real_name']}}" style="margin-bottom: 15px;">
                @if(!is_null($options['value']))
                    @if($options['multiple'])
                    <a href="javascript:{}" title="test" class="categories_parents @if( in_array($item['name'],$options['value'],true) ) active @endif" data-value="{{$item['name']}}" id="parent1">{{$item['title']}}</a>
                    @else
                    <a href="javascript:{}" title="test" class="categories_parents @if(  $options['value']== $item['name'] ) active @endif" data-value="{{$item['name']}}" id="parent1">{{$item['title']}}</a>

                    @endif
                @else

                    <a href="javascript:{}" title="test" class="categories_parents @if(  $key ==0) active @endif" data-value="{{$item['name']}}" id="parent1">{{$item['title']}}</a>
                @endif
                @if($options['multiple'])
                    <input type="hidden" @if($key ==0) name="{{$options['real_name']}}[]"  @endif class="{{$options['real_name']}}" value="{{$item['name']}}">
                @endif
            </label>

            @endforeach
            @if($options['multiple'] != true)
                <input type="hidden" name="{{$options['real_name']}}" id="{{$options['real_name']}}" value="{{$options['choices'][0]['name']}}">
            @endif

        </div>
    </div>
</div>
<script>
    $(function () {
        @if($options['readonly'])
            return ;
        @else
            @if($options['multiple'])

                $(".{{$options['real_name']}} a").click(function() {
                    if(  $(this).hasClass('active') ){
                        $(this).removeClass('active');
                        $(this).next().removeAttr('name')
                        if( $(".{{$options['real_name']}} a").hasClass('active') == false){
                            $(this ).addClass('active');
                            $(this).next().attr('name',"{{$options['real_name']}}[]")
                        }

                    }else{
                        $(this ).addClass('active');
                        $(this).next().attr('name',"{{$options['real_name']}}[]")
                    }
                });

            @else
                $(".{{$options['real_name']}} a").click(function() {
                    $(".{{$options['real_name']}} a ").removeClass('active');
                    $(this).addClass('active');
                    $("#{{$options['real_name']}}").val( $(this).attr('data-value'));
                });
            @endif
        @endif

});
</script>
