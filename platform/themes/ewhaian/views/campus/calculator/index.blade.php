<style>
.noborder{
    border: none!important;
}
.sm-form-control.form-small {
    height: 31px;
    line-height: normal;
    font-size: 12px;
    padding: 6px 14px;
}
.text-sm {
    font-size: 12px!important;
}
.table-bordered  td {
    text-align: center!important;
    border-bottom: 1px!important;
    font-weight: normal;
    padding-top: 6px!important;
    padding-bottom: 6px!important;
    border: 1px solid #EC1469;
}
.bgcolor-light-gray {
    background: #EEE!important;
}
.nopadding {
    padding: 0!important;
    line-height: 1.42857143;
    vertical-align: top;
    outline: none!important;
    border: 1px solid #EC1469;
}
.resume-grade {
    max-width: 60px;
}
.table-bordered select {
    height: 30px!important;
    background-color: transparent!important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: url('{{ Theme::asset()->url('img/sort_both.png') }}') no-repeat 95% 50%;
    padding: 0 .8em;
    padding-right: 20px;
    outline: none;
}
.sm-form-control {
    display: block;
    width: 100%;
    height: 38px;
    padding: 8px 14px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #DDD;
    border-radius: 0!important;
    -webkit-transition: border-color ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s;
    transition: border-color ease-in-out .15s;
    outline: none;
}
.table-bordered th, .table-bordered td {
    text-align: center!important;
    border-bottom: 1px!important;
    font-weight: normal;
    padding-top: 6px!important;
    padding-bottom: 6px!important;
    border: 1px solid #EC1469;
}
.bgcolor-gray {
    background: #EAEAEA!important;
}
.bgcolor-sr-light-blue {
    color: white;
    background-color: #EC1469;
}
.bgcolor-sr-light-blue p  {
    margin-bottom: 0px
}
.btn-add-grade-row {
    cursor: pointer;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #EC1469;
    background-color: #F4F4F4;
    border-color: #dee2e6;
    font-weight: bold;
}
#form_create .filter.align-items-end {
  justify-content: center;
}
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('campus.menu',['active'=>"calculator"]) !!}

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

                <div class="heading" style="display: flex;border-bottom: 2px solid #EC1469;">
                    <div class="heading__title" style="white-space: nowrap;">
                        평점계산기
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        설명들어길곳 설명들어길곳
                    </div>
                </div>

                <div class="content">
                    {!! Theme::partial('campus.chart', ['item' => $calculator->where('id',$id_calculator)->first(), 'calculator' => $calculator,
                     'point_average_all_detail' => $point_average_all_detail, 'total_credit_all_detail' => $total_credit_all_detail,]) !!}
                    @if (session('success'))
                        <div class="alert alert-success mt-4 mb-3">
                            {{ session('success') }}
                        </div>
                    @endif

                  @if (session('error'))
                    <div class="alert alert-danger mt-4 mb-3">
                      {{ session('error') }}
                    </div>
                  @endif

                    <ul class="nav nav-tabs nav-tabs-calculator mt-4" id="myTab" role="tablist" style="border-bottom: none;margin-bottom: 10px;">
                        @foreach ($calculator as $key => $item)
                        <li class="nav-item">
                            <a class="nav-link @if($id_calculator == $item->id )) active @endif bg-white-1" id="home-tab"
                                style="padding: .5rem 1rem;font-size: 12px;border-radius: .25rem;"
                                data-toggle="tab" href="#calculator_{{$item->id}}" rel="{{$item->id}}" role="tab" aria-controls="home" aria-selected="true">
                                {{ str_replace(' ', '', $item->name) }}
                            </a>
                            <i class="fa fa-times delete-calcualator" data-id="{{ $item->id }}" aria-hidden="true"></i>
                        </li>
                        @endforeach
                        <li class="nav-item">
                            <a class="nav-link bg-white-1 calculator_create" href="javascript:void(0)" title="Add"
                                style="padding: .5rem 1rem;font-size: 12px;">
                                추가
                            </a>
                        </li>
                      </ul>
                  <form action="{{route('campus.calculator_create_detail')}}" method="POST" id="form_create">
                    @csrf
                    <input type="hidden" id="id_active" name="id_active"/>
                      <div class="tab-content" id="myTabContent">
                        @foreach ($calculator as $key => $item)
                            <div class="tab-pane fade @if($id_calculator == $item->id) show active @endif " id="calculator_{{$item->id}}" role="tabpanel" aria-labelledby="home-tab">
{{--                                <form action="{{route('campus.calculator_create_detail')}}" method="POST" id="detailCalculate_{{$itconfirm_resetem->id}}">--}}
                                    {!! Theme::partial('campus.elements.calculator', ['item' => $item]) !!}
                            </div>
                        @endforeach
                          <div class="filter filter--1 align-items-end">
                            <div class="filter__item">
                              <button type="button" class=" btn btn-primary"
                                      data-toggle="modal" id="btnMainReset" data-target="#confirmReset"
                              >초기화</button>
                            </div>
                            <button type="button" id="btnCreateDetail" class=" btn btn-primary">저장</button>
                          </div>

                      </div>
                  </form>
                </div>
        </div>
    </div>

    </div>
</main>
<form action="{{route('campus.calculator_create')}}" method="post" id="calculator_create">
@csrf
</form>
<!-- Modal -->
<div class="modal fade" id="calculatorPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-md" role="document" style="text-align: center;">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('campus.calculator_factor')}}" method="post" id="formChangeFactor">
                    @csrf
                    <h3 class="text-bold mt-2 py-3 text-center">이수학점 변경</h3>
                    <div class="form-group form-group--1 mb-4">
                        <label for="factor" class="form-control">
                            {{-- <input type="text" id="number" name="factor" pattern="[0-9]" required placeholder="&nbsp;"> --}}
                            <input type=text id="factor" name="factor" oninput="this.value= [''].includes(this.value) ? this.value : this.value|0" required placeholder="&nbsp;">
                            <span class="form-control__label">이수학점 변경<span
                                    class="required">*</span></span>
                        </label>
                    </div>
                    <input type="hidden" name="calculator_id" id="calculator_id" value="{{$item->id}}">
                    <div class="button-group my-4 text-center">
                        <button type="button" class="btn btn-outline mr-lg-10"
                            data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary submit-factor">변경 하기</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmReset" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
    <div class="modal-content p-0">
      <div class="modal-body">
        <div class="d-lg-flex align-items-center mx-3">
          <div class="d-lg-flex align-items-start flex-grow-1">
            <div class="form-group form-group--1 flex-grow-1 mb-3">
                <form action="{{route('campus.calculator_reset')}}" method="post" id="calculator_reset">
                    @csrf
                    <input type="hidden" id="idActive" name="idActive"/>
                    <div class="md-form">
                        현재 학년의 학점을 초기화하시겠습니까?
                    </div>
                </form>
            </div>
          </div>
          <div class="button-group mb-2">
            <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('campus.calculator.cancel')}}</button>
            <button type="button" class="btn btn-primary" id="confirm_reset" >{{__('campus.calculator.confirm')}}</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<script>
    $(function() {
        $(document.body).on('click', '.pointer', function () {
            $id = $(this).attr("data-group");
            $item_id = $(this).attr("data-item-id");
            var html = `
            <tr>
                <td class="nopadding bgcolor-light-gray">
                    <select class="full-width noborder form-small not-dark noborder input-division" name="${$item_id}classification[${$id}][]" data-dropup-auto="false" >
                        <option value="1">전공필수</option>
                        <option value="2">전공선택</option>
                        <option value="3">교양</option>
                    </select>
                </td>
                <td class="nopadding">
                    <input type="text" name="${$item_id}description[${$id}][]" class="sm-form-control form-small not-dark noborder input-course" placeholder="수강과목" >
                </td>
                <td class="nopadding">
                    <input type="text" name="${$item_id}point[${$id}][]" class="sm-form-control resume-grade form-small not-dark noborder center input-credit" placeholder="학점" >
                </td>
                <td class="nopadding">
                    <select style="padding:0px" class="full-width noborder form-small not-dark noborder bgcolor-light-gray input-grades" name="${$item_id}grades[${$id}][]" data-dropup-auto="false" >
                        <option value="4.3">A+</option>
                        <option value="4.0">A</option>
                        <option value="3.7">A-</option>
                        <option value="3.3">B+</option>
                        <option value="3.0">B</option>
                        <option value="2.7">B-</option>
                        <option value="2.3">C+</option>
                        <option value="2.0">C</option>
                        <option value="1.7">C-</option>
                        <option value="1.3">D+</option>
                        <option value="1.0">D</option>
                        <option value="0.7">D-</option>
                        <option value="0">F</option>
                    </select>
                </td>
            </tr>
            `;
            $(html ).insertBefore($(this).parent().parent().parent());
        });

        $(document.body).on('click', '.remover', function () {
          $id = $(this).attr("data-group");

          button = $(this).parent().parent().parent();
          button.prev().prev().remove();
        });

        $(document.body).on('click', '#confirm_reset', function() {
            $('#calculator_reset').submit();
        })
        $(document.body).on('click', '.calculator_create', function() {
            $('#calculator_create').submit();
        })
        $(document.body).on('click', '.calculatorPopup', function() {
            $('#calculator_id').val($(this).attr('data-id'));
            $('#calculatorPopup').modal('show');
        })
        $(document.body).on('click', '.submit-factor', function() {
            $('#formChangeFactor').submit();
        })
        $(document).ready(function(){
            $('#btnMainReset').click(function(){
                var valID = $(".nav-link.active").attr("rel");
               $('#idActive').val(valID);

            })
        });

        $(document).ready(function(){
            $('#btnCreateDetail').click(function(){
                var valID = $(".nav-link.active").attr("rel");
                $('#id_active').val(valID);
                $('#form_create').submit();

            })
        });

        $(document.body).on('click', '.delete-calcualator', function() {
            let id = $(this).data('id');
            let urlDelete = "{{ route('campus.calculator_destroy', ['id' => ':id']) }}";
            urlDelete = urlDelete.replace(':id', id);
            let data = {'_token':$('meta[name="csrf-token"]').attr('content')};
            if (!confirm("{{ __('campus.calculator.confirm_delete_school_year') }}")) {
              return false;
            }

            $.ajax({
                type: 'DELETE',
                url: urlDelete,
                data: data
            }).done(function (response) {
              if (typeof response.message != 'undefined') {
                  alert(response.message);
                  setTimeout(function () {
                      window.location.href = response.url;
                  }, 500);
              }
            }).fail(function (response) {
                //
            }).always(function () {
                //
              });
        });
    })
</script>
