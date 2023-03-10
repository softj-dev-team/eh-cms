@if (is_null($votes))
    <a href="{{$route}}" title="votes" style="cursor: pointer">댓글이 없습니다</a>
@else
<a id="rateYo{{$id}}" href="{{$route}}" title="votes" style="cursor: pointer;margin: auto;"></a>
<script>
        $(function(){
            $("#rateYo{{$id}}").rateYo({

            rating: {{FLOOR($votes)}},
            readOnly: true,
            fullStar: true,
            ratedFill: "#EC1469",
            starWidth : '24px'

            });
        })
        </script>
@endif



