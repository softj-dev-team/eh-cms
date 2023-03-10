<style>
.item__new {
    position: absolute;
    top: 0;
    left: 0;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
}
.item__rectangle {
    position: absolute;
    top: -59px;
    left: -60px;
    width: 118px;
    height: 118px;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
    background: #EC1469;
    transform: rotate(45deg);
}
.icon-label {
  width: 2em;
  height: 2em;
  display: inline-block;
  border: 1px solid #FFFFFF;
  border-radius: 50%;
  color: #FFFFFF;
  text-align: center;
  line-height: 2em;
  font-weight: 700;
  font-size: 0.71429em;
}

.item__image {
    overflow: hidden;
}

.item__title {
  font-size: 1em;
}
</style>
<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          <div class="category-menu">
            <h4 class="category-menu__title">{{__('contents')}}</h4>
            <ul class="category-menu__links">
              <li class="category-menu__item active">
                <a href="{{route('contentsFE.list') }}" title="{{__('contents.contents_list')}}">{{__('contents.contents_list')}}</a>
              </li>
              <li class="category-menu__item">
                <a href="{{route('contentsFE.create')}}" title="{{__('contents.create_contents')}}">{{__('contents.create_contents')}}</a>
              </li>
            </ul>
          </div>
          <!-- end of category menu -->
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
                <li class="active">{!! $crumb['label'] !!}</li>
            @endif
            @endforeach
          </ul>

          <h3 class="title-main">{{__('contents')}}</h3>
          @if (session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif
          <div class="event-list">
            <div class="row">
              @if(count($contents) > 0 )

              @foreach ($contents as $item)
              <div class="col-lg-4 col-md-6">
                <div class="item">
                  <div class="item__image">
                    <div class="item__image mb-10">
                        @if(getNew($item->updated_at))
                            <div class="item__new">
                                <div class="item__rectangle" style="z-index: 1">

                                </div>
                                <div class="item__eye" style="z-index: 2;margin-right: -9px;">
                                <span class="icon-label">N</span>
                                    <div style="margin-left: -4px;">New</div>
                                </div>
                            </div>
                        @endif
                        <a href="{{route('contentsFE.edit',['id'=>$item->id])}}" title="{{ strip_tags($item->title) }}">
                        <img src="{{ get_image_url($item->banner, 'event-thumb')  }}" alt="" />
                        </a>
                        <a  href="javascript:void(0)" class="btn_remove_image deleteRoom" title="Remove room" data-toggle="modal" data-value="{{$item->id}}" data-target="#confirmDelete" >
                                <i class="fa fa-times"></i>
                        </a>
                    </div>

                    <div class="item__info">
                      <div class="item__date">
                        <svg width="15" height="17" aria-hidden="true" class="icon">
                          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                        </svg>
                        <?php
                          $start = date("Y-m-d",strtotime( $item->start) );
                          $end = date("Y-m-d",strtotime( $item->end) );

                          if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime( $start)) {
                            $item->start = "Today";
                          } else if (strtotime(  date("Y-m-d", strtotime('tomorrow'))  ) == strtotime($start) )  {
                            $item->start = "Tomorrow";
                          }else if(strtotime(  date("Y-m-d", strtotime('yesterday'))  ) == strtotime($start)){
                            $item->start = "Yesterday";
                          }else{
                            $item->start = date('d M | H:i a', strtotime($item->start)) ;
                          }

                        ?>
                        {{ $item->start }}
                      </div>
                      <div class="item__eye">
                        <svg width="16" height="10" aria-hidden="true" class="icon">
                          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                        </svg>
                        {{ $item->views}}
                      </div>
                    </div>
                  </div>

                  <div class="item__caption">
                    <h4 class="item__title">
                      <a href="{{route('contentsFE.edit',['id'=>$item->id])}}" title="{{ strip_tags($item->title) }}"> <div  style="  white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">{{ strip_tags($item->title)}} </div> </a>
                    </h4>

                     <div class="item__desc" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box; -webkit-line-clamp: 2; /* number of lines to show */-webkit-box-orient: vertical; min-height: 42px;">
                        {!! strip_tags(  $item->content ) !!}
                      </div>

                    <div class="item__datetime">
                      <span class="item__icon">
                        <svg width="10" height="10" aria-hidden="true" class="icon">
                          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_datetime"></use>
                        </svg>
                      </span>
                      <span class="item__datetime__detail">{{date('d M | H:i a', strtotime($start))}} -  {{ date('d M | h:i a', strtotime($end))}}</span>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
              @else
              <div class="col-lg-4 col-md-6">
                  <div class="item__desc">
                    <br>
                    <h3>{{__('contents.no_contents')}}</h3>
                  </div>
              </div>
              @endif
            </div>

            {!! Theme::partial('paging',['paging'=>$contents ]) !!}
            {{-- {!! Theme::partial('paging') !!} --}}
          </div>
        </div>
      </div>
    </div>
  </main>
 <!-- Modal -->
 <div class="modal fade modal--confirm" id="confirmDelete" tabindex="-1" role="dialog"
 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
   <div class="modal-content">
     <div class="modal-header align-items-center justify-content-lg-center">
       <span class="modal__key">
         <svg width="40" height="18" aria-hidden="true">
           <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
         </svg>
       </span>
     </div>
     <div class="modal-body">

       <div class="d-lg-flex align-items-center mx-3">
         <div class="d-lg-flex align-items-start flex-grow-1">
           <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
             <label for="hint" class="form-control">
               <input type=" text" id="hint" value="{{__('contents.confirm_delete')}}" placeholder="&nbsp;" readonly>
             </label>
           </div>
         </div>
         <form action="{{route('contentsFE.delete')}}" method="post">
           @csrf
           <div class="button-group mb-2">
               <input type="hidden" value="0" id="room_id" name="id">
               <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('contents.cancel')}}</button>
               <button type="submit" class="btn btn-primary">{{__('contents.delete')}}</button>
           </div>
       </form>
       </div>
     </div>
   </div>
 </div>
 </div>
 <script>
 $(function(){
     $('.deleteRoom').on('click',function(){
         $('#room_id').val( $(this).attr('data-value') )   ;
     })
 })
 </script>
