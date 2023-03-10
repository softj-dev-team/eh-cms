<style>
  .contents_banner:after {
    content: '';
    padding-top: 65% !important;
    display: block;
  }
</style>

@if ($slides != null )
  <div class="banner_content">
    @foreach (  $slides as $key => $item)
      <div class="banner__item" style="width: 440px; height: 277px; cursor: pointer;">
        <div class="slides_fe contents_banner"
             style="background-image: url('{{  get_image_url($item->banner, 'featured') }}');"
             data-val="{{route('contents.details',['idCategory'=>$item->categories_contents_id,'id'=>$item->id])}}">
          <img src="/themes/ewhaian/img/white.jpg" alt="slide 1"/>
        </div>
      </div>
    @endforeach
  </div>
@endif

<script>
  $(function () {
    $('.slides_fe').on('click', function () {
      if ($(this).attr('data-val') != null) {
        location.href = $(this).attr('data-val')
      }
    });
  })
</script>
