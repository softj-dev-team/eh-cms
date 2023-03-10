@if ($slides != null && Carbon\Carbon::parse($slides->start)->lte(Carbon\Carbon::now())  && Carbon\Carbon::parse($slides->end)->gte(Carbon\Carbon::now())   )
<div class="banner">
    @foreach (  $slides->getImageGallery()->images as $key => $item)
    <div class="banner__item" style="width: 880px; height: 277px; cursor: pointer;">
           <a href="{{ $item['url'] ?? '#' }}">
             <div class="slides_fe" style="background-image: url('{{$item['img']}}');" data-val="{{$item['description']}}">
               <img src="{{$item['img']}}" alt="{{$item['description']}}" />
             </div>
           </a>
      </div>
    @endforeach
</div>
@endif

<script>
    $(function(){
        $('.slides_fe').on('click',function(){
                if( $(this).attr('data-val') != null){
                    location.href =  $(this).attr('data-val')
                }
        })
    })
</script>
