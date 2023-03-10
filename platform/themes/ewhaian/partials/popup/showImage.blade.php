@if(isset($slides) && Carbon\Carbon::parse($slides->start)->lte(Carbon\Carbon::now())  && Carbon\Carbon::parse($slides->end)->gte(Carbon\Carbon::now()) )
  @if($slides->getImageGallery()->images)
  <!-- Modal -->
  <div class="modal fade modal--showImage" id="showImage" tabindex="-1" role="dialog"
       aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document" style="max-width: 300px;">
      <div class="modal-content">
        <div class="modal-body" style="text-align: right">
          <button class="mo-close" onclick="javascript:closeWin();"></button>
          <div class="row justify-content-lg-center ">
            <div class="col-lg-12">
              @foreach ($slides->getImageGallery()->images as $item)
                <div class="slides_image" style="cursor: pointer; text-align: center"
                     data-val="{{$item['description']}}">
                  <img src="{{$item['img']}}" alt="{{$item['description']}}"/>
                </div>
                <div style="text-align: right; margin-top: 10px;">
                  <input type="checkbox" id="md-ch" style="margin-right:10px;" value='Y'><label for="md-ch">오늘 하루 다시 열지 않기</label>
                </div>
              @endforeach
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  @endif
@else
  <!-- Modal -->
  <div class="modal fade modal--showImage" id="showImage" tabindex="-1" role="dialog"
       aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">

          <div class="row justify-content-lg-center ">
            <div class="col-lg-12">
              <div style="cursor: pointer; text-align: center" data-val="">
                <img src="/themes/ewhaian/img/white.jpg" alt=""/>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endif

<script>
  $(function () {
    //$('#showImage').modal('show');

    $('.slides_image').on('click', function () {
      if ($(this).attr('data-val') != null) {
        location.href = $(this).attr('data-val')
      }
    });
    $('.mo-close').click(function(){
      $(this).parents('.modal').trigger('click');
    });
  })
   function setCookie( name, value, expiredays ) {
      var todayDate = new Date();
      todayDate.setDate( todayDate.getDate() + expiredays );
      document.cookie = name + '=' + escape( value ) + '; path=/; expires=' + todayDate.toGMTString() + ';'
   }
   function getCookie(name){
       var obj = name + "=";
       var x = 0;
       while ( x <= document.cookie.length ){
           var y = (x+obj.length);
           if ( document.cookie.substring( x, y ) == obj ){
               if ((endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
                   endOfCookie = document.cookie.length;
               return unescape( document.cookie.substring( y, endOfCookie ) );
           }
           x = document.cookie.indexOf( " ", x ) + 1;
           if ( x == 0 ) break;
       }
       return "";
   }
   function closeWin(key){
       if($("#md-ch").prop("checked")){
           setCookie('showImage', 'Y' , 1 );
       }
       $("#showImage").modal('hide');
   }
   $(function(){
       if(getCookie("showImage") !="Y"){
           $("#showImage").modal('show');
       }
   });
</script>
