<style>
.ele-render-select2 .select2-container--classic .select2-selection--single .select2-selection__arrow b {

}
.ele-render-select2 .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #EC1469;
    border-radius: 4px;
}
.icon-search {
    width: 27px;
    height: 27px;
    padding: 5px;
    border-left: 1px solid #EC1469;
}
.ele-render-select2 .select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #EC1469;
    outline: 0;
}
.ele-render-select2 .select2-container--default .select2-selection--single .select2-selection__arrow {
    width: 28px;
}
.ele-render-select2 .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #EC1469;
    color: white;
}
.ele-render-select2 .select2-results__options{
    margin: 4px;
    border: 1px solid #EC1469;
    border-radius: 4px;
    border-bottom: 0px solid;
}
.ele-render-select2 .select2-results__option {
    border-bottom: 1px solid #EC1469;
}
.ele-render-select2 .search-result-item {
    display: flex;
    align-items: center;
}
.ele-render-select2 .search-result-item-text {
    flex: 1;
    display: flex;
    align-items: center;
}
.ele-render-select2 .search-result-item-text > div {
    padding: 0 10px;
}
.ele-render-select2 .search-result-item-text-name {
    font-weight: bolder;
}
.ele-render-select2 .select2-container--default .select2-results__option--highlighted[aria-selected] .icon-label {
    border-color: white;
    color: white;
}
.ele-render-select2 .select2-container--default .select2-results__option[aria-selected=true]  {
    background-color: #EC1469;
    color: white;
}
.ele-render-select2 .select2-container--default .select2-results__option[aria-selected=true] .icon-label {
    background-color: #EC1469;
    color: white;
    border-color: white;
}
/* The container */
.form-check  {
  display: block;
  position: relative;
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
  top: 25%;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: white;
  border: 1px solid #EC1469;
}
.form-check h6 {
    cursor: pointer;
    padding-left: 30px;
    margin-bottom: 13px;
}
.form-check .checkmark {
    cursor: pointer;
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
    margin: 0px 5px;
    border: 1px solid #EC1469;
    border-radius: 20px;
    display: flex;
}
.egarden-search {
    background-color: white;
    margin-bottom: 0px !important;
}
.select2-selection__clear {
    display: none!important;
}
.fa-star {
    color: #EC1469;
}
.ele-render-select2 .select2-container--default .select2-results__option--highlighted .fa-star {

    color: white;
    border-color: white;
}
</style>
<div>
    <div class="row justify-content-between">
      <div class="col-2">
        <div class="account__icon">
            <a href="{{route('egardenFE.room.list')}}" title="나의 E-화원">
                <span class="">나의 E-화원</span>
                <svg width="17" height="17" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting">
                    </use>
                </svg>
            </a>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
            <div class="input-group ele-render-select2">
                <select id="single-append-text" class="select2-allow-clear select2-hidden-accessible" tabindex="-1" aria-hidden="true" name="">
                    <option></option>
                </select>
            </div>
        </div>
        </div>
    </div>
  </div>

<script src="/themes/ewhaian/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    function formatState (state) {
        if (!state.id) {
            return state.name;
        }
        var name = state.name;
        var info = state.description;
        var isnew = state.isnew;
        var routeJoin = state.routejoin;
        var isjoined = state.isjoined;
        var staricon = '';
        var id = state.id;

        if(isjoined ==  true) {
            staricon = `<i class="fas fa-star"></i>`;
        } else {
            staricon = `<i class="far fa-star"></i>`;
        }

        if(isnew == true) {
            var dataNew = `
                        <div class="search-result-item-icon-new">
                            <span class="icon-label">N</span>
                        </div>
                     `;
        } else {
            var dataNew = ``;
        }

        var html = `
                <div class="search-result-item">
                    <div>
                        ${staricon}
                    </div>
                    <div class="search-result-item-text">
                        <div class="search-result-item-text-name">
                            ${name}
                        </div>
                        <div  class="search-result-item-text-info">
                            ${info}
                        </div>
                    </div>
                    ${dataNew}
                </div>`;
        var $state = $(html);
        return $state;
    };

    $('.select2-allow-clear').each(function () {
        let $parent = $(this).closest('.ele-render-select2');
        $(this).select2({
            width: '90%',
            dropdownParent: $parent,
            ajax: {
                url: "{{route('room.ajax.search')}}",
                dataType: 'json',
                delay: 250, // wait 250 milliseconds before triggering the request
                data: function (params) {
                    return {
                        keyword: params.term, // search term,
                        orderby: $('input[name="orderby"]:checked', $parent).val()
                    };
                },
                processResults: function (results) {
                    return {
                            results: results.items,
                        };
                },
                cache: false
            },
            placeholder: "Select a state",
            allowClear: true,
            templateResult: formatState,
            templateSelection :formatRepoSelection,
            minimumInputLength : 1
        });
    });

    $('.select2-selection__arrow').html(
        '<span class="form-control__icon"><svg aria-hidden="true" class="icon icon-search"> <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use> </svg></span>'
    );

    function formatRepoSelection (repo) {
        $('.select2-container--default .select2-selection--single .select2-selection__clear ').html(
            '<span class="form-control__icon"><svg aria-hidden="true" class="icon icon-search"> <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use> </svg></span>'
        );
        return  repo.name;
    }

    var check = 0;
    $('.select2-allow-clear').on('select2:open', function (e) {
        var html =`
        <div class="egarden-order">
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="inlineCheckbox1">
                     <h6 style="padding-left:5px;font-weight: bold;">취업 화원</h6>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="inlineCheckbox1">
                    <h6>이름순</h6>
                    <input class="form-check-input" type="radio" id="inlineCheckbox1" value="1" name="orderby" checked/>
                    <span class="checkmark"></span>
                </label>
            </div>

            <div class="form-check form-check-inline">
                <label class="form-check-label" for="inlineCheckbox2">
                    <h6>즐겨찾기순</h6>
                    <input class="form-check-input" type="radio" id="inlineCheckbox2" value="2" name="orderby" />
                    <span class="checkmark"></span>
                </label>
            </div>

            <div class="form-check form-check-inline">
                <label class="form-check-label" for="inlineCheckbox3">
                    <h6>최신글순</h6>
                    <input class="form-check-input" type="radio" id="inlineCheckbox3" value="3" name="orderby" />
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div style="margin:5px"><span>All Egarden list</span></div>
        `;
        if(check == 0) {
            $(html).insertAfter(".select2-search");
            check = 1;
        }
    });


    $(document.body).on('change', 'input[name="orderby"]', function () {
        var id = $(this).attr('id');
        var $parent = $(this).closest('.ele-render-select2');
        var $keywork = $('.select2-allow-clear', $parent).parent().find('.select2-search__field').val();

        check = 0;
        $('.select2-allow-clear', $parent).select2('destroy');
        $('.select2-allow-clear', $parent).select2({
            width: '90%',
            dropdownParent: $parent,
            ajax: {
                url: "{{route('room.ajax.search')}}",
                dataType: 'json',
                delay: 250, // wait 250 milliseconds before triggering the request
                data: function (params) {
                    return {
                        keyword: params.term, // search term,
                        orderby: $('input[name="orderby"]:checked', $parent).val()
                    };
                },
                processResults: function (results) {
                    return {
                            results: results.items,
                        };
                },
                cache: false
            },
            placeholder: "Select a state",
            allowClear: true,
            templateResult: formatState,
            templateSelection :formatRepoSelection,
            minimumInputLength: 1
        });

        $('.select2-allow-clear', $parent).select2('open');
        $('#'+id).prop('checked',true);
        $('.select2-selection__arrow').html(
            '<span class="form-control__icon"><svg aria-hidden="true" class="icon icon-search"> <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use> </svg></span>'
        );
       var $searchfield2 = $('.select2-allow-clear', $parent).parent().find('.select2-search__field');
        $searchfield2.val($keywork);
        $searchfield2.trigger('keyup');
    });

    $('.select2-allow-clear').on('select2:select', function (e) {
        e.preventDefault();
        var data = e.params.data;
        var id = data.id;
        var isjoined = data.isjoined;
        var routeJoin = data.routejoin;
        if(isjoined == true) {
            alert('You have joined the room');
        } else {
            e.preventDefault();
            var $this = $(this);
            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url:routeJoin,
                data:{
                        _token: "{{ csrf_token() }}",
                },
                dataType: 'html',
                success: function( data ) {
                    alert('Congratulations on joining the room');
                    window.location.reload();
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        }
    });

});
</script>
