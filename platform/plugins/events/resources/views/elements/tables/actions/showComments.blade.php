<i class="fa fa-comments"></i> <a href="{{ isset( $route ) ? $route  : route('events.comments.list',['id'=>$id])}}">{{__('comment')}}( {{$count}} )</a>
