@if(!empty($link ) || !empty($file_upload ))
<hr>

<div>
    @if (!empty($link ) && $link != 'null')
        @foreach ($link as $item)
            @php
              $idVideoYtb = getYoutubeVideoID($item);
            @endphp
            @if ($idVideoYtb != "")
                <div class="d-flex align-items-center mb-3">
                  <iframe width="880" height="409" src="https://www.youtube.com/embed/{{$idVideoYtb}}" frameborder="0"
                      allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                      allowfullscreen></iframe>
                </div>
            @else
                <div class="d-flex align-items-center mb-3">
                    <a href="{{$item}}" target="_blank">{{$item}}</a>
                </div>
            @endif
        @endforeach
    @endif

    <p>{{__('life.advertisements.attachiamenta')}} :   @if (empty($file_upload )) <p>첨부파일이 없습니다</p> @endif</p>
    @if (!empty($file_upload ) && $file_upload != 'null')
        @foreach ($file_upload as $key => $item)
                <div class="d-flex align-items-center mb-2">
                    <form id="my_form{{$key}}" action="{{route('download.attachiamenta')}}" method="post"  target="_blank" >
                        @csrf
                        <input type="hidden" name="url" value="{{$item}}">
                        <div style="position: relative">
                            <a  href="javascript:{}" onclick="document.getElementById('my_form{{$key}}').submit();">
                                <span class="icon-label" style="line-height: 4em;width: auto;height: 4em;border-radius: 5px;padding: 0 5px">
                                    <i class="fa fa-download"></i> {{basename($item)}}
                                </span>
                            </a>
                        </div>

                    </form>
                </div>
        @endforeach
    @endif

</div>
@endif
