<style>
        .input-group-image {
        margin-right: 17px;
    }
   .input-group-image .form-control--upload {
       height: 150px;
       width: 150px;
       max-width: 150px;
       background-size : 150px;
   }

</style>
{{-- <style>
    .popular_dragon {
        display: none;
    }
</style> --}}
<main id="main-content" data-view="flea-market" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <!-- flare menu -->
            {!! Theme::partial('life.menu',['active'=>"shelter"]) !!}
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
                            {{__('life.shelter_info')}}
                        </div>
                        <div class="heading__description">
                            {!!$description->description ?? __('life.shelter_info.no_have_description')!!}
                        </div>
                    </div>
                <form action="  @if(isset($shelter)) {{route('shelterFE.edit',['id'=>$shelter->id])}} @else {{route('shelterFE.create')}} @endif" id="my_form" method="post" enctype="multipart/form-data">
                    @csrf

                <div class="form form--border">
                    <h3 class="form__title">@if(isset($shelter)){{__('life.shelter_info.edit_shelter')}} @else {{__('life.shelter_info.create_shelter')}} @endif</h3>
                    <p class="text-right"><span class="required">* </span>{{__('life.shelter_info.required')}}</p>

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
                    @if (session('success'))
                    <div>
                        <ul>
                            <li class="alert alert-success">{{ session('success') }}</li>
                        </ul>
                    </div>
                    @endif
                    {!! Theme::partial('life.elements.categories',['categories'=>$categories,'selectedCategories'=>$selectedCategories ?? null]) !!}
                    {{-- <div class="form-group form-group--1">
                        <label for="item" class="form-control">
                            <input type=" text" id="item" placeholder="&nbsp;" name="title" value="{{$shelter->title ?? null}}" required>
                            <span class="form-control__label"> {{__('life.shelter_info.title')}} <span class="required">*</span></span>
                        </label>
                    </div> --}}



                    <div class="form-group form-group--1 d-sm-flex flex-wrap ">
                      <span class="text-bold mr-1 pt-4">{{__('life.shelter_info.title')}}</span><span class="required pt-4">* </span>

                      <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center ml-3">

                        <label for="item" class="form-control" style="line-height:2.5em">
                            <input type=" text" id="item" placeholder="&nbsp;" name="title"
                                value="{{$shelter->title ?? null}}" required>
                            {{-- <span class="form-control__label"> {{__('life.advertisements.title')}} <span class="required">*</span></span> --}}
                        </label>
                      </div>
                    </div>

                    <div class="form-group form-group--1 popular_dragon">
                        <label for="deposit" class="form-control">
                            <input type=" text" id="deposit" placeholder="&nbsp;" name="deposit" value="{{$shelter->deposit ?? null}}">
                            <span class="form-control__label">보증금</span>
                        </label>
                    </div>
                    <div class="form-group form-group--1 popular_dragon">
                        <label for="monthly_rent" class="form-control">
                            <input type="text" id="monthly_rent" placeholder="&nbsp;" name="monthly_rent" value="{{$shelter->monthly_rent ?? null}}">
                            <span class="form-control__label">월세</span>
                        </label>
                    </div>
                    <div class="form-group form-group--1 popular_dragon">
                        <label for="location" class="form-control">
                            <input type=" text" id="location" placeholder="&nbsp;" name="location" value="{{$shelter->location ?? null}}" >
                            <span class="form-control__label">위치</span>
                        </label>
                    </div>
                    <div class="form-group form-group--1 popular_dragon">
                        <label for="size" class="form-control">
                            <input type=" text" id="size" placeholder="&nbsp;" name="size" value="{{$shelter->size ?? null}}" >
                            <span class="form-control__label">크기</span>
                        </label>
                    </div>


                    <div class="popular_dragon">

                      {{-- multi checkbox for utility --}}
                        <div class="d-sm-flex flex-wrap align-items-center popular_dragon">
                            <div class=" d-flex align-items-center flex-grow-1">
                                <div class="form-group form-group--1 flex-grow-1">
                                    <label for="recruiment" class="form-control">
                                        <input type="text" id="utility[8]" placeholder="&nbsp;" name="utility[8]"
                                            value=" @if( ($shelter)  && !empty($shelter->utility[8] )) {{$shelter->utility[8]}} @endif">
                                        <span class="form-control__label">관리비</span>
                                    </label>
                                </div>
                                <div class="px-2"></div>
                            </div>

                            <div class="px-2">
                                <div class="custom-control custom-checkbox mx-3">
                                    <input type="checkbox" class="custom-control-input" id="utility[7]" name="utility[7]">
                                    <label class="custom-control-label" for="utility[7]">인터넷</label>
                                </div>
                            </div>

                        </div>
                        <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center ">
                            <span class="text-bold mr-4"></span>
                            <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                                <div class="custom-control mr-4">
                                    <input type="checkbox" name="utility[1]" value="1" class="custom-control-input" id="internet" @if( ($shelter)  &&   !empty($shelter->utility[1] )) checked @endif>
                                    <label class="custom-control-label" for="internet">인터넷</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="utility[2]" value="2" class="custom-control-input"  id="tv" @if( ($shelter)  &&   !empty($shelter->utility[2] )) checked @endif>
                                    <label class="custom-control-label" for="tv">유선TV</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="utility[3]" value="3" class="custom-control-input" id="cleaning_fee" @if( ($shelter)  &&   !empty($shelter->utility[3] )) checked @endif>
                                    <label class="custom-control-label" for="cleaning_fee">청소비</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="utility[4]" value="4" class="custom-control-input" id="watere_bill" @if( ($shelter)  &&   !empty($shelter->utility[4] )) checked @endif>
                                    <label class="custom-control-label" for="watere_bill">수도세</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="utility[5]" value="5" class="custom-control-input" id="gas_bill" @if(  ($shelter)  &&  !empty($shelter->utility[5] )) checked @endif>
                                    <label class="custom-control-label" for="gas_bill">도시가스</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="utility[6]" value="6" class="custom-control-input" id="electricity_bill"  @if( ($shelter)  &&   !empty($shelter->utility[6] )) checked @endif>
                                    <label class="custom-control-label" for="electricity_bill">전기세</label>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- end multi checkbox for utility --}}

                    <div class="form-group form-group--1 popular_dragon">
                        <label for="lease_period" class="form-control">
                            <input type=" text" id="lease_period" placeholder="&nbsp;" name="lease_period" value="{{$shelter->lease_period ?? null}}" >
                            <span class="form-control__label">계약기간</span>
                        </label>
                    </div>
                    <div class="form-group form-group--1 popular_dragon">
                        <label for="building_type" class="form-control">
                            <input type=" text" id="building_type" placeholder="&nbsp;" name="building_type" value="{{$shelter->building_type ?? null}}" >
                            <span class="form-control__label">건물형태</span>
                        </label>
                    </div>

                    <div class="form-group form-group--1 popular_dragon">
                        <label for="possible_moving_date" class="form-control">
                            <input type=" text" id="possible_moving_date" placeholder="&nbsp;" name="possible_moving_date" value="{{$shelter->possible_moving_date ?? null}}" >
                            <span class="form-control__label">입주가능일</span>
                        </label>
                    </div>
                    <div class="form-group form-group--1 popular_dragon">
                        <label for="heating_type" class="form-control">
                            <input type=" text" id="heating_type" placeholder="&nbsp;" name="heating_type" value="{{$shelter->heating_type ?? null}}" >
                            <span class="form-control__label">난방종류</span>
                        </label>
                    </div>

                    <!-- option-->
                    <div class="popular_dragon">
                        <span class="text-bold mr-4" style="margin-bottom: 10px">옵션항목</span>
                        <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center">
                            <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                                <div class="custom-control mr-4">
                                    <input type="checkbox" name="option[1]" value="1" class="custom-control-input" id="desk" @if( !empty($shelter->option[1] )) checked @endif>
                                    <label class="custom-control-label" for="desk">책상</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="option[2]" value="2" class="custom-control-input"  id="bed" @if( !empty($shelter->option[2] )) checked @endif>
                                    <label class="custom-control-label" for="bed">침대</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="option[3]" value="3" class="custom-control-input" id="refrigerator" @if( !empty($shelter->option[3] )) checked @endif>
                                    <label class="custom-control-label" for="refrigerator">냉장고</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="option[4]" value="4" class="custom-control-input" id="laundry_machine" @if( !empty($shelter->option[4] )) checked @endif>
                                    <label class="custom-control-label" for="laundry_machine">에어컨</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="option[5]" value="5" class="custom-control-input" id="ac" @if( !empty($shelter->option[5] )) checked @endif>
                                    <label class="custom-control-label" for="ac">에어컨</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="option[6]" value="6" class="custom-control-input" id="closet" @if( !empty($shelter->option[6] )) checked @endif>
                                    <label class="custom-control-label" for="closet">옷장</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="option[7]" value="7" class="custom-control-input" id="other" @if( !empty($shelter->option[7] )) checked @endif>
                                    <label class="custom-control-label" for="other">기타</label>
                                </div>
                                    <input type="hidden" name="option[8]" value="@if( !empty($shelter->option[8] )) {{$shelter->option[8]}} @endif" class="form-control form-control--auto flex-grow-1"
                                    placeholder="{{__('life.flea_market.enter_your_words')}}">

                            </div>
                        </div>
                    </div>



                    <div class="form-group form-group--1 popular_dragon">
                        <label for="contactUs" class="form-control">
                            <input type=" text" id="contactUs" placeholder="&nbsp;" name="contact" value="{{$shelter->contact ?? null}}" >
                            <span class="form-control__label">{{__('life.shelter_info.contact')}}</span>
                    </div>
                    <div class="form-group form-group--1 popular_dragon">
                        <label for="real_estate" class="form-control">
                            <input type=" text" id="real_estate" placeholder="&nbsp;" name="real_estate" value="{{$shelter->real_estate ?? null}}" >
                            <span class="form-control__label">중개업소</span>
                    </div>

                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                      <p class="text-bold mr-4">{{__('life.shelter_info.images')}}</p>
                      <div class="d-flex flex-wrap flex-grow-1">
                          <div class="input-group-image">
                              <input type="hidden" class="imagesValue" name="imagesValue[1]" value="{{($shelter && $shelter->images && $shelter->images[1]) ??  null}}" >
                              <input type="hidden" class="imagesBase64" name="imagesBase64[1]" value="{{$shelter && $shelter->images  && $shelter->images[1] ? '/'.$shelter->images[1]  :  null }}" >
                              <a class="btn_remove_image" title="{{__('life.shelter_info.remove_image')}}" @if( !isset($shelter->images[1] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                              <label for="uploadFile0" class="form-control form-control--upload"  style="{{ isset($shelter->images[1] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($shelter->images[1] ) ? get_image_url($shelter->images[1],'thumb') : ''  }}') ">
                                  <input type="file" name="images[1]" class="" id="uploadFile0"
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
                              <input type="hidden" class="imagesValue" name="imagesValue[2]" value="{{ $shelter && $shelter->images && $shelter->images[2] ??  null}}" >
                              <input type="hidden" class="imagesBase64" name="imagesBase64[2]" value="{{$shelter && $shelter->images &&  $shelter->images[2] ? '/'.$shelter->images[2]  :  null }}" >
                              <a class="btn_remove_image" title="{{__('life.shelter_info.remove_image')}}" @if( !isset($shelter->images[2] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                              <label for="uploadFile1" class="form-control form-control--upload"  style="{{ isset($shelter->images[2] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($shelter->images[2] ) ? get_image_url($shelter->images[2],'thumb') : ''  }}') ">
                                  <input type="file" name="images[2]" class="" id="uploadFile1"
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
                              <input type="hidden" class="imagesValue" name="imagesValue[3]" value="{{$shelter && $shelter->images && $shelter->images[3] ??  null}}" >
                              <input type="hidden" class="imagesBase64" name="imagesBase64[3]" value="{{$shelter && $shelter->images && $shelter->images[3] ? '/'.$shelter->images[3]  :  null }}" >
                              <a class="btn_remove_image" title="{{__('life.shelter_info.remove_image')}}" @if( !isset($shelter->images[3] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                              <label for="uploadFile2" class="form-control form-control--upload"  style="{{ isset($shelter->images[3] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($shelter->images[3] ) ? get_image_url($shelter->images[3],'thumb') : ''  }}') ">
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
                              <input type="hidden" class="imagesValue" name="imagesValue[4]" value="{{ ($shelter && $shelter->images && $shelter->images[4]) ??  null}}" >
                              <input type="hidden" class="imagesBase64" name="imagesBase64[4]" value="{{ ($shelter && $shelter->images && $shelter->images[4]) ? '/'.$shelter->images[4]  :  null }}" >
                              <a class="btn_remove_image" title="{{__('life.shelter_info.remove_image')}}" @if( !isset($shelter->images[4] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                              <label for="uploadFile3" class="form-control form-control--upload"  style="{{ isset($shelter->images[4] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($shelter->images[4] ) ? get_image_url($shelter->images[4],'thumb') : ''  }}') ">
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
                              <input type="hidden" class="imagesValue" name="imagesValue[5]" value="{{$shelter && $shelter->images  && $shelter->images[5] ??  null}}" >
                              <input type="hidden" class="imagesBase64" name="imagesBase64[5]" value="{{$shelter && $shelter->images  && $shelter->images[5] ? '/'.$shelter->images[5]  :  null }}" >
                              <a class="btn_remove_image" title="{{__('life.shelter_info.remove_image')}}" @if( !isset($shelter->images[5] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                              <label for="uploadFile4" class="form-control form-control--upload" style="{{ isset($shelter->images[5] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($shelter->images[5] ) ? get_image_url($shelter->images[5],'thumb') : ''  }}') ">
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

                    <div class="form-group form-group--1" >
                        <div class="d-flex justify-content-between" style="margin-bottom: 10px;">
                          <label class="text-bold">{{__('life.shelter_info.detail')}} </label>
                          <label>게시글 미리보기 이미지는 상세내용이 아닌 위쪽의 이미지 삽입 기능을 사용하셔야 합니다.</label>
                        </div>
                        <div class="flex-grow-1">
                            <textarea class="ckeditor" name="detail" >{{$shelter->detail ?? null}}</textarea>
                            <script>
                              CKEDITOR.replace( 'detail', {
                                  filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                  filebrowserUploadMethod: 'form'
                              });
                            </script>
                        </div>
                    </div>
                    <div style="margin: 20px 0">
                        {!! Theme::partial('upload_link_file',['link'=> $shelter ? $shelter->link : '',
                         'file_upload' => $shelter ? $shelter->file_upload : '' ]) !!}
                    </div>
                    {{-- <div class="d-sm-flex">
                        <span class="text-bold mr-4 pr-2" >{{__('life.shelter_info.status')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($shelter) && $shelter->status == "publish" ) selected @endif>{{__('life.shelter_info.publish')}}</option>
                                    <option value="draft" @if(isset($shelter) && $shelter->status == "draft" ) selected @endif>{{__('life.shelter_info.draft')}}</option>
                                </select>
                        </div>
                    </div> --}}

                    <div class=" d-flex flex-wrap custom-checkbox mb-4">
                      <span class="text-bold mr-4">{{__('life.part-time_job.open_bulletin_board_policy')}}</span>
                      <div class="custom-control mr-4">
                          <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree"
                              checked required>
                          <label class="custom-control-label" for="agree">{{__('life.part-time_job.agree')}}</label>
                      </div>
                      {!! Theme::partial('policy_pattern') !!}
                  </div>

                </div>

{{--                {!! Theme::partial('right_mouse_function',--}}
{{--                    [--}}
{{--                        'item' => $shelter ?? null,--}}
{{--                        'can_active_empathy' => 0,--}}
{{--                    ])--}}
{{--                !!}--}}
                {!! Theme::partial('submit_form',[
                    'is_validate_image' => $shelter  ? '0' : 1,
                    'route_preview' => route('shelterFE.preview'),
                    'route_back' => route('life.shelter_list'),
                    'idPreview' =>  $shelter ? $shelter->id : '',
                ]) !!}
                {{-- <div class="text-center">
                    <a href="javascript:{}" class="btn btn-outline temporary">{{__('life.shelter_info.temporary_save')}}</a>
                    <a href="javascript:{}" class="btn btn-outline preview">{{__('life.shelter_info.preview')}}</a>
                    <a href="javascript:{}" class="btn btn-primary save" >{{__('life.shelter_info.enrollment')}}</a>
                </div> --}}
            </form>
            </div>
        </div>
    </div>
</main>

<script>
    $(function(){
        $('.popular-search .popular__item a').on('click' ,function() {
            if($(this).text() == '용달')  {
                $('.popular_dragon').css('display','none');
            } else {
                $('.popular_dragon').css('display','block');
            }
        });

        if($('input[name="option[6]"]').attr('checked')){
            $('input[name="option[7]"]').prop('type','text');
            $('input[name="option[7]"]').focus();
        }

        //show it when the checkbox is clicked
        $('input[name="option[7]"]').on('click', function () {
            if ($(this).prop('checked')) {
                $('input[name="option[8]"]').prop('type','text')
                $('input[name="option[8]"]').fadeIn();
                $('input[name="option[8]"]').focus();
            } else {
                $('input[name="option[8]"]').fadeOut();
                $('input[name="option[8]"]').prop('type','hidden')
            }
        });

        //show it when the checkbox is clicked
        $('input[name="utility[7]"]').on('click', function () {
            if ($(this).prop('checked')) {
                $('input[name="utility[8]"]').prop('disabled','true');
            } else {
                $('input[name="utility[8]"]').removeAttr('disabled');
            }
        });

    })

</script>
