<style>
  .editor {
    padding-left:0;
    padding-right:0;
  }
</style>
<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- intro menu -->
                {!! Theme::partial('intro.menu',['categories'=>$categories,'detail'=>$detail]) !!}
                <!-- end of intro menu -->
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                    @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                    <li>
                        <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    @else
                    <li>
                      <a href="#">{!! $crumb['label'] !!}</a>
                      <svg width="4" height="6" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                      </svg>
                    </li>
                    @endif
                    @endforeach
                  @if(in_array($detail->id, [4,5,6]))
                      <li>ABOUT 운영진</li>
                      @else
                        <li>이화이언 소개</li>
                      @endif
                </ul>

                <div class="eh-introduction">
                    <h3 class="eh-introduction__title title-main">{{$detail->title}}</h3>
                    <div class="editor">
                        @if(!is_null($detail->link))
                        <?php $idVideoYtb = getIDVideoYoutube($detail->link); ?>
                            <iframe width="775" height="409" src="https://www.youtube.com/embed/{{$idVideoYtb}}" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        @endif
                        <div style="margin-top: 1.71429em;">
                            {!! $detail->detail !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
	function collapseList(event) {
    if ($('#nav-list-menu').hasClass('show')) {
      event.target.className = 'fas fa-angle-right d-lg-none';
    } else {
      event.target.className = 'fas fa-angle-down d-lg-none';
    }

		$('#nav-list-menu').collapse('toggle');
	}
</script>
