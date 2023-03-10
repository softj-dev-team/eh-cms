<style>
  .left-banner.desktop {
    display: block;
  }

  .left-banner.mobile {
    display: none;
  }

  @media screen and (max-width: 992px) {
    .left-banner.desktop {
      display: none;
    }
    .left-banner.mobile {
      display: block;
    }
  }
</style>

@if(!is_null($composer_SLIDES_ACCOUNT->getImageGallery()->images)
    && Carbon\Carbon::parse($composer_SLIDES_ACCOUNT->start)->lte(Carbon\Carbon::now())
    && Carbon\Carbon::parse($composer_SLIDES_ACCOUNT->end)->gte(Carbon\Carbon::now())
)
  <section class="left-banner {{$class}}">
    {{-- composer_SLIDES_ACCOUNT --}}
    @foreach ($composer_SLIDES_ACCOUNT->getImageGallery()->images as $key => $item)
      @switch($key)
        @case(0)
        <div class="slides_contents left_banner"
             style="background-image: url('{{$item["img"]}}'); height:50px; margin: 10px 0; cursor: pointer; "
             data-href="{{$item["description"]}}" data-route="{{$route ?? route('home.clicker')}}" data-key="{{$key}}"
             title="{{$item["description"]}}">
          <a href="{{ $item['url'] ?? '#' }}"></a>
        </div>
        @break
        @case(1)
        <div>
          <div class="slides_contents left_banner"
               style="background-image: url('{{$item["img"]}}'); height:100px; margin-bottom:  10px; cursor: pointer;"
               data-href="{{$item["description"]}}" data-route="{{$route ?? route('home.clicker')}}" data-key="{{$key}}"
               title="{{$item["description"]}}">
                      <a href="{{ $item['url'] ?? '#' }}"></a>
          </div>
        </div>
        @break
        @case(2)
        <div class="slides_contents left_banner"
             style="background-image: url('{{$item["img"]}}'); height:200px; margin-bottom: 10px; cursor: pointer;"
             data-href="{{$item["description"]}}" data-route="{{$route ?? route('home.clicker')}}" data-key="{{$key}}"
             title="{{$item["description"]}}">
                    <a href="{{ $item['url'] ?? '#' }}"></a>
        </div>
        @break
        @default
      @endswitch
    @endforeach
  </section>
@endif

<script>
  $(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.left_banner').on('click', function (e) {
      let $this = $(this);
      e.preventDefault();
      $.ajax({
        type: 'POST',
        url: $this.data('route'),
        data: {
          key: $this.data('key')
        },
        success: function (data) {
          if (data['status'] == true) {
            window.location.href = $this.data('href');
          }
        }
      }).always(function () {
        $this.css('pointer-events', 'auto').data('isRunAjax', false);
      });
    })
  })
</script>
