@if ($paging->hasPages() && $paging->currentPage() <= $paging->lastPage() )
    <!-- Pagination -->
<nav aria-label="Pagination">
    <ul class="pagination justify-content-center pagination--custom">
      <li class="page-item @if($paging->currentPage() == 1) disabled @endif">
        <a class="page-link" href="{{ $paging->url(1)}}" title="First">
          <svg width="9" height="13" aria-hidden="true" class="icon">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_first"></use>
          </svg>
        </a>
      </li>
      <li class="page-item  @if($paging->currentPage() == 1) disabled @endif ">
        <a class="page-link" href="{{$paging->previousPageUrl()}}" title="Prev">
          <svg width="9" height="13" aria-hidden="true">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_prev"></use>
          </svg>
        </a>
      </li>
      @if($paging->lastPage() <= 10)
        @for ($i = 1; $i <= $paging->lastPage();$i++)
            <li class="page-item @if($paging->currentPage() == $i) active @endif">
                <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
            </li>
        @endfor
      @else
            <?php
                $mod =  (int)fmod($paging->currentPage(), 10);
                $quotient = (int)( $paging->currentPage()/10 );
                if (  $quotient  -1 < 0) {
                    $quotient = 0;
                }

                $countLastPageCurrentPage = $paging->lastPage() - $paging->currentPage();

                $itemPage = 10;
                if($countLastPageCurrentPage < 10){
                    if(($paging->currentPage() + $countLastPageCurrentPage)  >= (($quotient + 1) * 10 )){
                        $itemPage = 10;
                    }else{
                        $itemPage = (int)fmod( $paging->lastPage(), $quotient * 10);
                        if($mod == 1){
                            if($itemPage > 1){
                                $itemPage = $itemPage - 1;
                            }

                        }

                    }

                }else{
                    if($mod == 1){
                        $itemPage = 9;
                    }
                }
            ?>
            @switch($mod)
                @case(0)
                    @if($quotient > 0)
                        @for ($i = ($quotient-1) * 10 + 1; $i <=  ($quotient - 1) * 10 + 10 ;$i++)
                            <li class="page-item @if($paging->currentPage() == $i) active @endif">
                                <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
                            </li>
                        @endfor
                    @else
                        @for ($i = ($quotient) * 10 + 1; $i <=  ($quotient) * 10 + 10 ;$i++)
                            <li class="page-item @if($paging->currentPage() == $i) active @endif">
                                <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
                            </li>
                        @endfor
                    @endif
                    @break
                @case(1)
                    @if($paging->currentPage() < $paging->lastPage())
                        @for ($i = $paging->currentPage(); $i <= $paging->currentPage() + $itemPage;$i++)
                            <li class="page-item @if($paging->currentPage() == $i) active @endif">
                                <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
                            </li>
                        @endfor
                        @break
                    @endif
                @default
                  @for ($i = $quotient * 10 + 1; $i <=  $quotient * 10 + $itemPage ;$i++)
                    <li class="page-item @if($paging->currentPage() == $i) active @endif">
                        <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
                    </li>
                    @endfor
                @break
            @endswitch
      @endif


      <li class="page-item @if($paging->currentPage() == $paging->lastPage()) disabled @endif">
        <a class="page-link rotate-180" href="{{$paging->nextPageUrl()}}" title="Next">
          <svg width="9" height="13" aria-hidden="true" class="icon">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_prev"></use>
          </svg>
        </a>
      </li>
      <li class="page-item @if($paging->currentPage() == $paging->lastPage()) disabled @endif  ">
        <a class="page-link rotate-180" href="{{$paging->url($paging->lastPage())}}" title="Last">
          <svg width="9" height="13" aria-hidden="true" class="icon">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_first"></use>
          </svg>
        </a>
      </li>

    </ul>
  </nav>
  @endif
<script>
  $(document).ready(async function () {
    const input = $("input[name='keyword']");
    if (input.length > 0) {
      let oldVal = input.val();
      const api = await $.post('/api/v1/members/swear-word');
      api.forEach((item) => {
        oldVal = oldVal.replace(item, '^^');
      })
      await input.val(oldVal);
    }
  });
</script>
