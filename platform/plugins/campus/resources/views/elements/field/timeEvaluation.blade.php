<div data-upload-file class="upload-file mb-3">
    <div data-template="" class="data-origin-template d-none">
        <div class="mb-2">
            <input type="hidden" class="template_id" value="#numberTemplate">
{{--            <input type="hidden" name="datetime[#template_day][day]" value="#dayValue">--}}
{{--            <input type="hidden" name="datetime[#template_from][from]" value="#fromValue">--}}
{{--            <input type="hidden" name="datetime[#template_to][to]" value="#toValue">  --}}
            <div class="d-flex align-items-center">
              <span class="upload-file__name flex-grow-1" data-file-type="#fileType">요일</span>
              <span class="upload-file__name flex-grow-1" data-file-type="#fileType">시작시간</span>
              <span class="upload-file__name flex-grow-1" data-file-type="#fileType">종료시간</span>
              <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file type="button">
                <i class="fas fa-minus-square"></i>
              </button>
            </div>
            <div class="d-flex align-items-center">
              <div class="flex-grow-1">
                <input class="form-control" name="datetime[#template_day][day]" value="#dayValue">
              </div>
              <div class="flex-grow-1">
                <input class="form-control" name="datetime[#template_from][from]" value="#fromValue">
              </div>
              <div class="flex-grow-1">
                <input class="form-control" name="datetime[#template_to][to]" value="#toValue">
              </div>
            </div>
        </div>
    </div>

    <div data-uploaded-content="" class="data-clone-child ">

        @if (!empty($options['data-selected'] ))
          @foreach ($options['data-selected'] as $key => $item)
            @if (!empty($item['day']) && !empty($item['from']) && !empty($item['to']))
            <div class="d-flex align-items-center mb-2">
                <input type="hidden" class="template_id" value="{{$key}}">
                <input type="hidden" name="datetime[{{$key}}][day]" value="{{$item['day']}}">
                <input type="hidden" name="datetime[{{$key}}][from]" value="{{$item['from']}}">
                <input type="hidden" name="datetime[{{$key}}][to]" value="{{$item['to']}}">
                <span class="upload-file__name flex-grow-1" data-file-type="#fileType">{{ucfirst($item['day'] )}}</span>
                <span class="upload-file__name flex-grow-1"
                    data-file-type="#fileType">{{  fmod( $item['from'],1)  > 0.0 ? floor($item['from']).':30' : floor( $item['from']).':00' }}</span>
                <span class="upload-file__name flex-grow-1"
                    data-file-type="#fileType">{{  fmod( $item['to'],1)  > 0.0 ?   floor($item['to']).':30' : floor( $item['to']).':00' }}</span>
                <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file type="button">
                    <i class="fas fa-minus-square"></i>
                </button>
            </div>
            @endif
        @endforeach
        @endif
    </div>

</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm" style="margin-bottom: 10px ">
            <label for="status" class="{{$options['label_attr']['class']}}"
                aria-required="true">{{$options['label']['1']}}</label>

            <select class="form-control select-full select2-hidden-accessible" data-select-day tabindex="-1"
                aria-hidden="true">
                @foreach ($options['data']['day'] as $item)
                <option value="{{$item->name}}">{{ ucfirst ($item->name ) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm" style="margin-bottom: 10px ">
            <label for="status" class="{{$options['label_attr']['class']}}"
                aria-required="true">{{$options['label']['2']}}</label>

            <select class="form-control select-full select2-hidden-accessible" data-select-from tabindex="-1"
                aria-hidden="true">
                @for ($i = $options['data']['from']; $i <= $options['data']['to']; $i++) <option value="{{$i}}">
                    {{$i.':00'}}

                    <option value="{{$i+0.5}}">{{$i.':30'}}</option>

                    @endfor
            </select>
        </div>
        <div class="col-sm" style="margin-bottom: 10px ">
            <label for="status" class="{{$options['label_attr']['class']}}"
                aria-required="true">{{$options['label']['3']}}</label>

            <select class="form-control select-full select2-hidden-accessible" data-select-to tabindex="-1"
                aria-hidden="true">
                @for ($i = $options['data']['from']; $i <= $options['data']['to']; $i++) <option value="{{$i+0.5}}">
                    {{$i.':30'}}

                    <option value="{{$i+1}}">{{($i+1).':00'}}</option>

                    @endfor
            </select>
        </div>
        <div clas="col-sm" style="padding: 13px">
            <button class="btn btn-primary btn-icon mr-3 mt-3" data-add-link type="button">
                <i class="fas fa-plus-square"></i>
            </button>
        </div>
    </div>
    <span id="datetime-error" class="invalid-feedback"> </span>
</div>
<script>
    const   template = $('[data-template]').html(),
            container = $('[data-uploaded-content]');
    var template_id =       @if (!empty($options['data-selected'] )) <?php end( $options['data-selected']); echo key($options['data-selected']) ?> @else 0 @endif ;

    var datetime = @if (!empty($options['data-selected'] )) <?php echo json_encode( $options['data-selected'] ) ?> @else [] @endif ;

    $('[data-add-link]').on('click', function(){
      var  day  =  $('[data-select-day] option:selected' ).html(),
        from =  $('[data-select-from] option:selected' ).html(),
        to   =  $('[data-select-to] option:selected' ).html(),

        dayValue   =   $('[data-select-day] option:selected' ).val() ,
        fromValue   =  parseFloat( $('[data-select-from] option:selected' ).val() ),
        toValue   = parseFloat( $('[data-select-to] option:selected' ).val()  );

        var item = {
                'day' : dayValue,
                'from' : fromValue,
                'to' : toValue,
            }

        switch (checkTimeLineBeforeAdd(datetime,item)) {
            case 0:
              $('[data-template]').removeClass('d-none');
                template_id ++;
                container.append(
                    template
                            .replace('#On',day)
                            .replace('#From',from)
                            .replace('#To',to)
                            .replace('#dayValue',dayValue)
                            .replace('#fromValue',fromValue)
                            .replace('#toValue',toValue)
                            .replace('#template_day',template_id)
                            .replace('#template_from',template_id)
                            .replace('#template_to',template_id)
                            .replace('#numberTemplate',template_id)
                );
                $('#datetime-error').css('display','none');
                $('.data-origin-template').remove();
                datetime[template_id] = item;
                break;
            case 1:
                $('#datetime-error').css('display','block');
                $('#datetime-error').html('The From less than The To');
                break;
            case 2:
                $('#datetime-error').css('display','block');
                $('#datetime-error').html('Timeline exist, please choose another timeline');
                break;

            default:
                break;
        }

    });

    $(document).on('click','.data-clone-child .data-remove-file', function(){
       if( $(this).parent().parent().find('.upload-file__name').attr('data-file-type') == 'file'){
        $(this).parent().parent().find('input:hidden').val($(this).attr('data-file-url'));
       }

        delete datetime[$(this).parent().parent().find('.template_id').val()];
        $(this).parent().parent().find('.align-items-center').remove();
    });

    function checkTimeLineBeforeAdd(data,value){
        let datetime = [];
        if(value['from'] >= value['to']) return 1; // The From less than The To
        for (const [key, item] of Object.entries(data)) {
            if(item['day'] == value['day'] ){
                datetime.push(item);
            }
        }

        return checkTimeLineExst(datetime,value);
    }

    function checkTimeLineExst(data,value){
        for (const [key, $item] of Object.entries(data)) {
            let $from = value['from'],
                $to = value['to'];

            // a, b là khoản time line đã có từ trước
            //From  nằm trong khoản a và b -> false

            if ($item['from'] < $from && $from < $item['to']) {
                return 2;
            }

            //To  nằm trong khoản a và b -> false
            if ($item['from'] < $to && $to < $item['to']) {
                return 2;
            }

            // a và b nằm trong khoản From To
            if ($from <= $item['from'] && $item['to'] <= $to) {
                return 2;
            }
        }

        return 0;//true
    }

</script>
