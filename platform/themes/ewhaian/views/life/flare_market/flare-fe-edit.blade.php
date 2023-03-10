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
                <form action="{{route('flareMarketFE.edit',['id'=>$flare->id])}}" id="my_form" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form form--border">
                        <h3 class="form__title" id="form__title_flare">{{ old('categories.1') ?  $flare->getCategoriesByID(old('categories.1'))->name  : $flare->getCategories()->name }} </h3>
                        <p class="text-right"><span class="required">* </span>{{__('life.flea_market.required')}}</p>

                        @if ($errors->any())
                        <div>
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
                        {{-- <div class="d-sm-flex" style="margin-bottom: 10px;">
                            <span class="text-bold mr-4 pr-2">{{__('life.flea_market.type_details')}}<span class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="categories[1]" id="categories-flare">
                                    @foreach ($categories as $item)
                                        @if($item->parent_id == 1)
                                            <option value="{{$item->id}}" @if($item->id == ( old('categories.1') ?? $flare->getCategories(1)->id ) ) selected @endif>{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <input type="hidden" name="categories[1]" id="categories1" value="{{$flare->categories[1]}}">
                        {!!Theme::partial('life.elements.categories',['categories'=>$categories,'selectedCategories'=> ( old('categories.2') ?  $flare->getCategoriesByID(old('categories.2'))  : $flare->getCategories(2) ),'parent'=>2] )!!}
                        <div class="form-group form-group--1">
                            <label for="item" class="form-control">
                                <input type=" text" id="item" placeholder="&nbsp;" name="title"
                                    value="{{  old('title') ?? $flare->title}}">
                                <span class="form-control__label">{{__('life.flea_market.title')}}<span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="product" class="form-control">
                                <input type=" text" id="product" placeholder="&nbsp;" name="product" required value="{{  old('product') ?? $flare->product}}">
                                <span class="form-control__label">거래물품<span class="required">*</span></span>
                            </label>
                        </div>

                        @if($flare->categories[1] == $categories->first()->id )
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
                                            name="purchase_date" autocomplete="off" required
                                            value="{{Carbon\Carbon::parse( $flare->purchase_date ?? today())->format('Y.m.d')}} ">
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="purchasingTime" class="form-control">
                                <input type=" text" id="purchasingTime" placeholder="&nbsp;" name="purchasing_price"
                                    value="{{ old('purchasing_price') ?? $flare->purchasing_price}}">
                                <span class="form-control__label">{{__('life.flea_market.purchasing_price')}}<span
                                        class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="purchase_location" class="form-control">
                                <input type=" text" id="purchase_location" placeholder="&nbsp;" name="purchase_location"
                                value="{{ old('purchase_location') ?? $flare->purchase_location}}" required>
                                <span class="form-control__label">구입장소<span class="required">*</span></span>
                            </label>
                        </div>
                        @endif
                        @if($flare->categories[1] == $categories->first()->id )
                        <div class="form-group form-group--1">
                            <label for="quality" class="form-control">
                                <input type=" text" id="quality" placeholder="&nbsp;" name="quality"
                                value="{{ old('quality') ?? $flare->quality}}"    required>
                                <span class="form-control__label">물품상태<span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="purchasingPrice" class="form-control">
                                <input type=" text" id="purchasingPrice" placeholder="&nbsp;" name="reason_selling"
                                    value="{{old('reason_selling') ?? $flare->reason_selling}}">
                                <span class="form-control__label">{{__('life.flea_market.reason_selling')}}<span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="where" class="form-control">
                                <input type=" text" id="where" placeholder="&nbsp;" name="sale_price"
                                    value="{{old('sale_price') ?? $flare->sale_price}}">
                                <span class="form-control__label">{{__('life.flea_market.sale_price')}}<span class="required">*</span></span>
                            </label>
                        </div>
                        @endif
                        <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                            <p class="text-bold mr-4">{{__('life.flea_market.image')}}</p>
                            <div class="d-flex flex-wrap flex-grow-1">
                                <div class="input-group-image">
                                    <input type="hidden" class="imagesValue" name="imagesValue[1]" value="{{$flare->images[1] ??  null }}" >
                                    <input type="hidden" class="imagesBase64" name="imagesBase64[1]" value="{{$flare->images[1] ? '/'.$flare->images[1]  :  null }}" >
                                    <a class="btn_remove_image" title="Remove image"   @if( !isset($flare->images[1] ) )  style="display : none" @endif ><i class="fa fa-times"></i></a>
                                    <label for="uploadFile0" class="form-control form-control--upload" style="{{ isset($flare->images[1] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset( $flare->images[1] ) ? get_image_url($flare->images[1],'thumb') : ''  }}') ">
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
                                    <input type="hidden" class="imagesValue" name="imagesValue[2]" value="{{$flare->images[2] ??  null}}" >
                                    <input type="hidden" class="imagesBase64" name="imagesBase64[2]" value="{{$flare->images[2] ? '/'.$flare->images[2]  :  null }}" >
                                    <a class="btn_remove_image" title="Remove image" @if( !isset($flare->images[2] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                    <label for="uploadFile1" class="form-control form-control--upload" style="{{ isset($flare->images[2] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($flare->images[2] ) ? get_image_url($flare->images[2],'thumb') : ''  }}') ">
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
                                    <input type="hidden" class="imagesValue" name="imagesValue[3]" value="{{$flare->images[3] ??  null}}" >
                                    <input type="hidden" class="imagesBase64" name="imagesBase64[3]" value="{{$flare->images[3] ? '/'.$flare->images[3]  :  null }}" >
                                    <a class="btn_remove_image" title="Remove image" @if( !isset( $flare->images[3] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                    <label for="uploadFile2" class="form-control form-control--upload" style="{{ isset($flare->images[3]) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{isset( $flare->images[3] ) ? get_image_url($flare->images[3],'thumb') : ''  }}') ">
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
                                    <input type="hidden" class="imagesValue" name="imagesValue[4]" value="{{$flare->images[4] ??  null}}" >
                                    <input type="hidden" class="imagesBase64" name="imagesBase64[4]" value="{{$flare->images[4] ? '/'.$flare->images[4]  :  null }}" >
                                    <a class="btn_remove_image" title="Remove image" @if( !isset($flare->images[4] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                    <label for="uploadFile3" class="form-control form-control--upload" style="{{ isset($flare->images[4] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($flare->images[4]) ? get_image_url($flare->images[4],'thumb') : ''  }}') ">
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
                                    <input type="hidden" class="imagesValue" name="imagesValue[]" value="{{$flare->images[5] ??  null}}" >
                                    <input type="hidden" class="imagesBase64" name="imagesBase64[5]" value="{{$flare->images[5] ? '/'.$flare->images[5]  :  null }}" >
                                    <a class="btn_remove_image" title="Remove image" @if( !isset($flare->images[5]) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                    <label for="uploadFile4" class="form-control form-control--upload" style="{{ isset($flare->images[5] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($flare->images[5] )? get_image_url($flare->images[5],'thumb') : ''  }}') ">
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
                                    <input type="checkbox" name="exchange[1]" value="1" class="custom-control-input"
                                        id="directDeal" @if( !empty($flare->exchange[1] )) checked @endif>
                                    <label class="custom-control-label" for="directDeal">{{__('life.flea_market.direct_deals')}}</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="exchange[2]" value="2" class="custom-control-input"
                                        id="delivery" @if(   !empty($flare->exchange[2] )) checked @endif>
                                    <label class="custom-control-label" for="delivery">{{__('life.flea_market.delivery')}}</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="exchange[3]" value="3" class="custom-control-input"
                                        id="locker" @if(   !empty($flare->exchange[3] )) checked @endif>
                                    <label class="custom-control-label" for="locker">{{__('life.flea_market.locker')}}</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" name="exchange[4]" value="4" class="custom-control-input"
                                        id="other" @if(   !empty($flare->exchange[4] )) checked @endif>
                                    <label class="custom-control-label" for="other">{{__('life.flea_market.other')}}</label>
                                </div>
                                <input type="hidden" name="exchange[5]"
                                    value="{{ $flare->exchange[5] }}"
                                    class="form-control form-control--auto flex-grow-1" placeholder="거래 방법을 입력하세요.">
                            </div>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="contactUs" class="form-control">
                                <input type=" text" id="contactUs" placeholder="&nbsp;" name="contact"
                                value="{{ old('contact') ?? $flare->contact}}"  required>
                                <span class="form-control__label">{{__('life.flea_market.contact')}}<span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="d-sm-flex">
                            <span class="text-bold mr-4 pr-2" style="display: block;margin-bottom: 10px;">기타사항<span class="required">*</span></span>
                            <div class="flex-grow-1" style="max-width: 749.53px;">
                                <textarea class="ckeditor" name="detail" id="content">{!! old('detail') ?? $flare->detail !!}</textarea>
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
                            <span class="text-bold mr-4 pr-2">{{__('life.flea_market.status')}} <span class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3 status" style="color: black !important;" id="special_status">
                                    @if($flare->categories[1]  == $categories->first()->id )
                                    <option value="publish" @if(isset($flare) && $flare->status == "publish" ) selected @endif>{{__('life.flea_market.status.on_sale')}}</option>
                                    @endif
                                    <option value="pending" @if(isset($flare) && $flare->status == "pending" ) selected @endif>{{__('life.flea_market.status.in_transit')}}</option>
                                    <option value="completed" @if(isset($flare) && $flare->status == "completed" ) selected @endif>{{__('life.flea_market.status.transaction_completed')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class=" d-flex flex-wrap custom-checkbox mb-4">
                        <a href="{{getPolicyPage()}}" class="text-bold mr-4" target="_blank">{{__('life.flea_market.open_bulletin_board_policy')}}</a>
                        <div class="custom-control mr-4">
                            <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree"
                                checked required>
                            <label class="custom-control-label" for="agree">{{__('life.flea_market.agree')}}</label>
                        </div>
                        {{-- <span>
                            <span class="required">*</span>
                            {{__('life.flea_market.view_bulletin_board_policy')}}
                        </span> --}}
                    </div>

                    {!! Theme::partial('submit_form',[
                        'is_validate_image' => 0,
                        'route_preview' => route('flareMarketFE.preview'),
                        'route_back' => route('life.flare_market_list'),
                        'idPreview' =>  $flare ? $flare->id : '',
                    ]) !!}
                    {{-- <div class="text-center">
                        <a href="javascript:{}" class="btn btn-outline temporary">{{__('life.flea_market.temporary_save')}}</a>
                        <a href="javascript:{}" class="btn btn-outline preview">{{__('life.flea_market.preview')}}</a>
                        <a href="javascript:{}" class="btn btn-primary save" >{{__('life.flea_market.enrollment')}}</a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // $('.temporary').on('click',function(){
    //     $('#my_form').attr('action',"{{route('flareMarketFE.edit',['id'=>$flare->id])}}");
    //     $('#my_form').attr('target','_self');

    //     switch ($('.status').val()) {
    //         case 'publish':
    //             $('.status').val('draft');
    //             break;
    //         case 'draft':
    //             $('.status').val('pending');
    //             break;
    //         default:
    //             break;
    //     }
    //          $('#my_form').submit();
    // })

    // $('.preview').on('click',function(){
    //     $('#my_form').attr('action','{{route('flareMarketFE.preview')}}');

    //     $('#my_form').attr('target','_blank');
    //     $('#my_form').submit();
    // })

    // $('.save').on('click',function(){
    //     $('#my_form').attr('action',"{{route('flareMarketFE.edit',['id'=>$flare->id])}}");
    //     $('#my_form').removeAttr('target');
    //     $('#my_form').submit();
    // })
    </script>
