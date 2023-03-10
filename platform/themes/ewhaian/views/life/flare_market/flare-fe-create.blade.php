<style>
        .input-group-image {
        margin-right: 17px;
    }
   .input-group-image .form-control--upload {
       height: 150px;
       width: 150px;
       max-width: 150px;
       background-size : 150px
   }
</style>


<script>
  $(document).ready(function(){
    $('#startDate').datepicker('setDate', 'today');
  });
</script>

<main id="main-content" data-view="flea-market" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <!-- flare menu -->
            <div class="sidebar-template__control">
                <!-- flea FE menu -->
                {!! Theme::partial('life.menu',['active'=>"flare_market_list"]) !!}
                <!-- end of Flea Market FE menu -->
            </div>
            <!-- end of flare menu -->
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
                <div class="heading">
                    <div class="heading__title">
                        {{__('life.flea_market')}}
                    </div>
                    <div class="heading__description">
                        {!!$description->description ?? __('life.flea_market.no_have_description')!!}
                    </div>
                </div>
                <form action="{{route('flareMarketFE.create',['categoryId'=>$categoryId])}}" id="my_form" method="post" enctype="multipart/form-data">
                    @csrf

                <div class="form form--border">
                    {!! Theme::partial('life.elements.switch_header',[
                        'firstParent' => $parent,
                        'parent_id' => $categoryId,
                        'route' => 'flareMarketFE.create'
                    ])!!}

                    <p class="text-right"><span class="required">* </span>{{__('life.flea_market.required')}}</p>

                    @if ($errors->any())
                    <div >
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (session('err'))
                    <div>
                        <ul>
                            <li class="alert alert-danger">{{ session('err') }}</li>
                        </ul>
                    </div>
                    @endif
                    <input type="hidden" name="categories[1]" id="categories1" value="{{$categoryId}}">
                    {!! Theme::partial('life.elements.categories',['categories'=>$categories,'selectedCategories'=>$selectedCategories ?? null,'parent'=>2]) !!}
                    {{-- <div class="form-group form-group--1">
                        <label for="item" class="form-control">
                            <input type=" text" id="item" placeholder="&nbsp;" name="title" required>
                            <span class="form-control__label">{{__('life.flea_market.title')}} <span class="required">*</span></span>
                        </label>
                    </div> --}}

                    <div class="form-group form-group--1 d-sm-flex flex-wrap ">
                      <span class="text-bold mr-1 pt-4">{{__('life.flea_market.title')}}</span><span class="required pt-4">* </span>
                      <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center ml-3">
                        <label for="item" class="form-control" style="line-height:2.5em">
                            <input type="text" id="item" placeholder="&nbsp;" name="title" required>
                            {{-- <span class="form-control__label"> {{__('life.advertisements.title')}} <span class="required">*</span></span> --}}
                        </label>
                      </div>
                    </div>

                    <div class="form-group form-group--1 d-sm-flex flex-wrap ">
                        <span class="text-bold mr-1 pt-4">거래물품</span><span class="required pt-4">* </span>
                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center ml-3">
                            <label for="product" class="form-control" style="line-height:2.5em">
                                <input type="text" id="product" placeholder="&nbsp;" name="product" required>
                            </label>
                        </div>
                    </div>

{{--                    <div class="form-group form-group--1">--}}
{{--                        <label for="product" class="form-control">--}}
{{--                            <input type="text" id="product" placeholder="&nbsp;" name="product" required>--}}
{{--                            <span class="form-control__label">거래물품<span class="required">*</span></span>--}}
{{--                        </label>--}}
{{--                    </div>--}}

                    @if($categoryId == $categories->first()->id )
                    <div class="d-sm-flex align-items-center">
                        <span class="text-bold mr-3">구입시기 <span class="required">*</span></span>
                        <div class="flex-grow-1 mx-3">
                          <div class="d-flex align-items-center">
                            <div class="form-group form-group--search  flex-grow-1 mr-1">
                                <span class="form-control__icon form-control__icon--gray">
                                  <svg width="20" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender"></use>
                                  </svg>
                                </span>
                                <input data-datepicker-start type="text" class="form-control startDate" id="startDate"
                                        name="purchase_date" value="" autocomplete="off" required>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="purchasingTime" class="form-control">
                            <input type=" text" id="purchasingTime" placeholder="&nbsp;" name="purchasing_price" required>
                            <span class="form-control__label">{{__('life.flea_market.purchasing_price')}}<span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="purchase_location" class="form-control">
                            <input type=" text" id="purchase_location" placeholder="&nbsp;" name="purchase_location" required>
                            <span class="form-control__label">구입장소<span class="required">*</span></span>
                        </label>
                    </div>
                    @endif
                    @if($categoryId == $categories->first()->id )
                    <div class="form-group form-group--1">
                        <label for="quality" class="form-control">
                            <input type=" text" id="quality" placeholder="&nbsp;" name="quality" required>
                            <span class="form-control__label">물품상태<span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="purchasingPrice" class="form-control">
                            <input type=" text" id="purchasingPrice" placeholder="&nbsp;" name="reason_selling" required>
                            <span class="form-control__label">{{__('life.flea_market.reason_selling')}}<span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="where" class="form-control">
                            <input type=" text" id="where" placeholder="&nbsp;" name="sale_price" required>
                            <span class="form-control__label">{{__('life.flea_market.sale_price')}}<span class="required">*</span></span>
                        </label>
                    </div>
                    @endif
                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                        <p class="text-bold mr-4">{{__('life.flea_market.image')}}</p>
                        <div class="d-flex flex-wrap flex-grow-1">
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[1]" value="{{null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[1]" value="{{null}}" >
                                <a class="btn_remove_image" title="{{__('life.flea_market.remove_image')}}" style="display: none"><i class="fa fa-times"></i></a>
                                <label for="uploadFile0" class="form-control form-control--upload image-box">
                                    <input type="file" name="images[1]" class="image-data-upload" id="uploadFile0"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[2]" value="{{null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[2]" value="{{null}}" >
                                <a class="btn_remove_image" title="{{__('life.flea_market.remove_image')}}" style="display: none"><i class="fa fa-times"></i></a>
                                <label for="uploadFile1" class="form-control form-control--upload">
                                    <input type="file" name="images[2]" class="image-data-upload" id="uploadFile1"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[3]" value="{{null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[3]" value="{{null}}" >
                                <a class="btn_remove_image" title="{{__('life.flea_market.remove_image')}}" style="display: none"><i class="fa fa-times"></i></a>
                                <label for="uploadFile2" class="form-control form-control--upload">
                                    <input type="file" name="images[3]" class="" id="uploadFile2"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>

                            </div>
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[4]" value="{{null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[4]" value="{{null}}" >
                                <a class="btn_remove_image" title="{{__('life.flea_market.remove_image')}}" style="display: none"><i class="fa fa-times"></i></a>
                                <label for="uploadFile3" class="form-control form-control--upload">
                                    <input type="file" name="images[4]" class="" id="uploadFile3"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[5]" value="{{null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[5]" value="{{null}}" >
                                <a class="btn_remove_image" title="{{__('life.flea_market.remove_image')}}" style="display: none"><i class="fa fa-times"></i></a>
                                <label for="uploadFile4" class="form-control form-control--upload">
                                    <input type="file" name="images[5]" class="" id="uploadFile4"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center">
                        <span class="text-bold mr-4">{{__('life.flea_market.how_to_trade')}}</span>
                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                            <div class="custom-control mr-4">
                                <input type="checkbox" name="exchange[1]" value="1" class="custom-control-input" id="directDeal" checked>
                                <label class="custom-control-label" for="directDeal">{{__('life.flea_market.direct_deals')}}</label>
                            </div>
                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                <input type="checkbox" name="exchange[2]" value="2" class="custom-control-input"  id="delivery" checked>
                                <label class="custom-control-label" for="delivery">{{__('life.flea_market.delivery')}}</label>
                            </div>
                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                <input type="checkbox" name="exchange[3]" value="3" class="custom-control-input" id="locker" checked>
                                <label class="custom-control-label" for="locker">{{__('life.flea_market.locker')}}</label>
                            </div>
                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                <input type="checkbox" name="exchange[4]" value="4" class="custom-control-input" id="other">
                                <label class="custom-control-label" for="other">{{__('life.flea_market.other')}}</label>
                            </div>
                            <input type="hidden" name="exchange[5]" value="" class="form-control form-control--auto flex-grow-1"
                                placeholder="거래 방법을 입력하세요.">
                        </div>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="contactUs" class="form-control">
                            <input type=" text" id="contactUs" placeholder="&nbsp;" name="contact" required>
                            <span class="form-control__label">{{__('life.flea_market.contact')}}<span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="d-sm-flex">
                        <span class="text-bold mr-4 pr-2" style="display: block;margin-bottom: 10px;">기타사항<span class="required">*</span></span>
                        <div class="flex-grow-1" style="max-width: 749.53px;">
                            <textarea class="ckeditor" name="detail" id="content"></textarea>
                            <script>
                              CKEDITOR.replace( 'detail', {
                                  filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                  filebrowserUploadMethod: 'form'
                              });
                            </script>
                        </div>
                    </div>
                    <div style="margin: 20px 0">
                        {!! Theme::partial('upload_link_file',['link'=> $flare ? $flare->link : '',
                         'file_upload' => $flare ? $flare->file_upload : '' ]) !!}
                    </div>
                    <div class="d-sm-flex">
                        <span class="text-bold mr-4 pr-2" >{{__('life.flea_market.status')}}<span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" id="special_status" style="color: black !important;">
                                    @if($categoryId == $categories->first()->id )
                                    <option value="publish" @if(isset($flare) && $flare->status == "publish" ) selected @endif>{{__('life.flea_market.status.on_sale')}}</option>
                                    @endif
                                    <option value="pending" @if(isset($flare) && $flare->status == "pending" ) selected @endif>{{__('life.flea_market.status.in_transit')}}</option>
                                    <option value="completed" @if(isset($flare) && $flare->status == "completed" ) selected @endif>{{__('life.flea_market.status.transaction_completed')}}</option>
                                </select>
                        </div>
                    </div>
                </div>
                <div class=" d-flex flex-wrap custom-checkbox mb-4">
                    {{-- <a href="{{getPolicyPage()}}" class="text-bold mr-4" target="_blank">{{__('life.flea_market.open_bulletin_board_policy')}}</a> --}}
                    <span class="text-bold mr-4">{{__('life.flea_market.open_bulletin_board_policy')}}</span>
                    <div class="custom-control mr-4">
                        <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree" checked required>
                        <label class="custom-control-label" for="agree">{{__('life.flea_market.agree')}}</label>
                    </div>
                    {{-- <span>
                        <span class="required">*</span>
                        {{__('life.flea_market.view_bulletin_board_policy')}}
                    </span> --}}
                    {!! Theme::partial('policy_pattern') !!}
                </div>

                {!! Theme::partial('submit_form',[
                    'is_validate_image' => 0,
                    'route_preview' => route('flareMarketFE.preview'),
                    'route_back' => route('life.flare_market_list'),
                    'idPreview' =>  $flare ? $flare->id : '',
                ]) !!}
            </form>
            </div>
        </div>
    </div>
</main>

<script>
   $(document).on('click', '.btn_remove_image', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('.image-box').find('.preview-image-wrapper').hide();
                var check = $(event.currentTarget).next().find("input").val('');
            });
</script>
