<style>
/* The container */
.form-check  {
  display: block;
  position: relative;
  padding-left: 35px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.form-check  input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 1%;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: white;
  border: 1px solid #EC1469;
  cursor: pointer;
}
.form-check h6 {
    cursor: pointer;
    margin-bottom: 0;
}
/* On mouse-over, add a grey background color */
.form-check:hover input ~ .checkmark {
  background-color: #EC1469;
}

/* When the checkbox is checked, add a blue background */
.form-check  input:checked ~ .checkmark {
  background-color: #EC1469;
}

/* Create the checkmark/indicator (hidden when not checked) */
.form-check :after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.form-check  input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.form-check  .checkmark:after {
  left: 7px;
  top: 3px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
.form-check-inline .form-check-input {
    position: absolute;
}
.egarden-order {
    margin-bottom: 30px;
    border: 1px solid #EC1469;
        border-radius: 20px;
        display: flex;
}
.egarden-search {
    background-color: white;
    margin-bottom: 0px !important;
}
.egarden-order div {
    margin: 15px 10px;
}
.form-check-label {
    margin-bottom: 0;
    position: relative;
    padding-left: 40px;
    cursor: pointer;
}

</style>
@if (isset($canSearch) && $canSearch == true)
<div>
    <div class="row ">
        <div class="col">
            <form action="{{$route}}" method="GET" id="form-search-1">
                <div class="filter align-items-center egarden-search">
                    <div class="filter__item filter__title mr-3">카테고리</div>
                    <div class="filter__item d-flex align-items-center flex-grow-1">
                        <select class="form-control form-control--select mx-3" name="categories_room_id" style="width: 53%" id ="categories_room_id">
                            <option value="" selected disabled hidden>카테고리를 선택해주세요</option>
                            @foreach ($room->categoreis as $item)
                                <option value="{{$item->id}}"
                                @if (request('categories_room_id') == $item->id)
                                    selected
                                @endif
                                >{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="type" value="{{request('type')}}">
                <input type="hidden" name="keyword" value="{{request('keyword')}}">
                <input type="hidden" name="orderby" value="{{request('orderby')}}">
            </form>
        </div>
        <div class="col">
            <form action="{{$route}}" method="GET" id="form-search-2">
                <div class="filter align-items-center egarden-search">
                    <div class="filter__item filter__title mr-3" style="width: 35px">검색</div>
                    <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                        <select class="form-control form-control--select mx-3" name="type" value="">
                            <option value="0"
                                @if (request('type') == 0)
                                    selected
                                @endif
                            >제목</option>
                            <option value="1"
                            @if (request('type') == 1)
                                selected
                            @endif
                            >상세내용</option>
                        </select>
                        <div class="form-group form-group--search  flex-grow-1  mx-3">
                            <a href="javascript:{}" onclick="document.getElementById('form-search-2').submit();">
                            <span class="form-control__icon">
                                <svg width="14" height="14" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                                </svg>
                            </span>
                            </a>
                            <input type="text" class="form-control keyword" placeholder="검색 내용을 입력하세요." name="keyword" value="{{request('keyword')}}" maxlength="50">
                        </div>
                    </div>
                    <input type="hidden" name="orderby" value="{{request('orderby')}}">
                    <input type="hidden" name="categories_room_id" value="{{request('categories_room_id')}}">
                    <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search" style="display:none">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#categories_room_id').on('change', function () {
            $('#form-search-1').submit();
        });
    })
</script>
@endif

@if (isset($canOrder) && $canOrder == true)
<form action="{{$route}}" method="GET" id="form-search-3">
    <div class="egarden-order">
        <div class="form-check form-check-inline" style="margin: 23px 10px 0 0;padding-left: 10px; font-size: 14px; font-weight: bold ">
            <span>주문</span>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label" for="inlineCheckbox1">
                <h6>{{$labelOrderby1}}</h6>
                <input class="form-check-input" type="radio" id="inlineCheckbox1" value="1" name="orderby"
                @if (request('orderby') == 1)
                    checked
                @endif>
                <span class="checkmark"></span>
            </label>
        </div>

        <div class="form-check form-check-inline">
            <label class="form-check-label" for="inlineCheckbox2">
                <h6>{{$labelOrderby2}}</h6>
                <input class="form-check-input" type="radio" id="inlineCheckbox2" value="2" name="orderby"
                @if (request('orderby') == 2)
                    checked
                @endif>
                <span class="checkmark"></span>
            </label>
        </div>

        <div class="form-check form-check-inline">
            <label class="form-check-label" for="inlineCheckbox3">
                <h6>{{$labelOrderby3}}</h6>
                <input class="form-check-input" type="radio" id="inlineCheckbox3" value="3" name="orderby"
                @if (request('orderby') == 3)
                    checked
                @endif>
                <span class="checkmark"></span>
            </label>
        </div>
    </div>
    <input type="hidden" name="type" value="{{request('type')}}">
    <input type="hidden" name="keyword" value="{{request('keyword')}}">
    <input type="hidden" name="categories_room_id" value="{{request('categories_room_id')}}">
    </form>
    <script>
        $(function() {
            $("input[name='orderby']").click(function() {
                $('#form-search-3').submit();
            });
        })
    </script>

@endif

