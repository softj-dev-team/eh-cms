@php $item = $options['value']  @endphp

<div class="form-group">
  <label for="reason_option" class="control-label ">{{ __('reason_option') }}</label>
  <div class="form-control-static" id="reason_option" data-counter="120" readonly="1">
    @switch($item->reason_option)
      @case(1)
      <span>훌리건 의심</span>
      @break
      @case(2)
      <span>회원에 대한 욕설 혹은 저격</span>
      @break
      @case(3)
      <span>허위사실 유포</span>
      @break
      @case(4)
      <span>게시 자료의 저작권 위반</span>
      @break
      @case(5)
      <span>일반인 신상정보 게시</span>
      @break
      @case(6)
      <span>지나친 홍보 또는 상거래 유도</span>
      @break
      @case(7)
      <span>다른 게시판에 적절한 게시글</span>
      @break
      @case(8)
      <span>기타</span>
      @break
      @default
      <span>훌리건 의심</span>
      @break
    @endswitch
  </div>
</div>

<div class="form-group">
  <label for="type_report" class="control-label ">{{ __('member.report_type') }}</label>
  <div class="form-control-static" id="type_report" data-counter="120" readonly="1">
    @switch ($item->type_report)
      @case (1)
        <span>{{ __('post') }}</span>
        @break;
      @case (2)
        <span>{{ __('comment') }}</span>
        @break;
      @default
        <span>{{ __('post') }}</span>
        @break;
    @endswitch
  </div>
</div>

<div class="form-group">
  <label for="type_post" class="control-label ">{{ __('type_post') }}</label>
  <div class="form-control-static" id="type_post" data-counter="120" readonly="1">
    @switch ($item->type_post)
      @case (1)
        <span>{{ __('event.menu__title') }}</span>
        @break;
      @case (2)
        <span>{{ __('event_comment') }}</span>
        @break;
      @case (3)
      <span>{{ __('home.contents') }}</span>
        @break;
      @case (4)
      <span>{{ __('home.open_space') }}</span>
        @break;
      @case (5)
      <span>{{ __('home.flea_market') }}</span>
        @break;
      @case (6)
      <span>{{ __('header.part-time_job') }}</span>
        @break;
      @case (7)
      <span>{{ __('home.shelter_info') }}</span>
        @break;
      @case (8)
      <span>{{ __('home.advertisements') }}</span>
        @break;
      @case (9)
      <span>{{ __('garden') }}</span>
        @break;
      @case (10)
      <span>{{ __('header.study_room') }}</span>
        @break;
      @case (11)
      <span>{{ __('campus.old_genealogy') }}</span>
        @break;
      @case (12)
      <span>{{ __('campus.genealogy') }}</span>
        @break;
      @case (13)
      <span>{{ __('campus.evaluation') }}</span>
        @break;
      @default
        <span>{{ __('other_report') }}</span>
        @break;
    @endswitch
  </div>
</div>

@if ($item->type_report == 1)
  <!--1: post . 2: comments -->
  <div class="form-group">
    <label for="reason" class="control-label ">글번호 </label>
    <div class="form-control-static" id="reason" readonly="1">
      {!! $item->id_post !!}
    </div>
  </div>
  @else
  <div class="form-group">
    <label for="reason" class="control-label ">댓글번호 </label>
    <div class="form-control-static" id="reason" readonly="1">
      {!! $item->id_post !!}
    </div>
  </div>
@endif
  {{--    reporter--}}
  <div class="form-group">
    <label for="reason" class="control-label ">{{__('person_report_id')}}</label>
    <div class="form-control-static" id="reason" readonly="1">
      {!! $item->person_report_id !!}
    </div>
  </div>

  {{--    reported--}}
  <div class="form-group">
    <label for="reason" class="control-label ">{{__('reported_id')}} </label>
    <div class="form-control-static" id="reason" readonly="1">
      {!! $item->reported_id !!}
    </div>
  </div>


@if($item->reason && $item->reason != 'N/A')
  <div class="form-group">
    <label for="reason" class="control-label ">{{ __('reason') }} </label>
    <div class="form-control-static" id="reason" readonly="1">
      {!! $item->reason !!}
    </div>
  </div>
@endif

<div class="form-group">
  <label for="created_at" class="control-label ">{{ __('core/base::tables.created_at') }}</label>
  <div class="form-control-static" id="reason" readonly="1">
    {!! date_from_database($item->created_at, config('core.base.general.date_format.date')) !!}
  </div>
</div>

<div class="form-group">
  <label class="control-label ">{{ __('solve_report') }}</label>
  <div style="border: 1px solid #EC1469; padding: 10px">

    @if ($item->type_report == 1)
      <!--1: post . 2: comments -->
      @php
        $post = $item->getPost($item->type_post , $item->id_post);
      @endphp

      @if(is_null($post))
        <div>
          <span style="color: #EC1469">{{ __('post_was_deleted') }}</span>
        </div>
      @else
        @switch($item->type_post)
          @case(1)
            <!-- Events -->
            <div>
              <a href="{{route('events.edit',$item->id_post)}}" target="blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(2)
            <!-- EventsCmt -->
            <div>
              <a href="{{route('events.cmt.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(3)
            <!-- Contents -->
            <div>
              <a href="{{route('contents.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(4)
            <!-- Life/OpenSpace -->
            <div>
              <a href="{{route('life.open.space.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(5)
            <!-- Life/Flare -->
            <div>
              <a href="{{route('life.flare.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(6)
            <!-- Life/Jobs-PartTime -->
            <div>
              <a href="{{route('life.jobs_part_time.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(7)
            <!-- Life/Shelter -->
            <div>
              <a href="{{route('life.shelter.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(8)
            <!-- Life/Advertisements -->
            <div>
              <a href="{{route('life.advertisements.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @case(9)
          <!-- garden -->
            <div>
              <a href="{{route('garden.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
            @case(10)
          <!-- StudyRoom -->
            <div>
              <a href="{{route('campus.study_room.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
            @case(11)
          <!-- OldGenealogy -->
            <div>
              <a href="{{route('campus.old.genealogy.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
            @case(12)
          <!-- Genealogy -->
            <div>
              <a href="{{route('campus.genealogy.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
            @case(13)
          <!-- Evaluation -->
            <div>
              <a href="{{route('campus.evaluation.edit',$item->id_post)}}" target="_blank">{{ __("link_detail") }} </a>
            </div>
            @break
          @default
        @endswitch
      @endif
    @else
      @php
        $comment = $item->getComment($item->type_post , $item->id_post);
      @endphp
      @if(isset($comment))
        @if($comment->status != 'publish')
          <label label style=" text-decoration: line-through;"> {{$comment->content}}</label>
        @else
          <label> {{$comment->content}}</label>
          <div class="widget-body">
            <button type="submit" name="submit" value="apply" class="btn btn-danger">
              <i class="fa fa-trash"></i> {{ __('delete_comment') }}
            </button>
          </div>
        @endif
      @endif
    @endif
  </div>

  <div class="widget-body">
    <button class="btn btn-danger" type="button" onclick="setHooligan()">
      <i class="fa fa-ban"></i> {{ __('set_user_to_hooligan') }}
    </button>
  </div>

  <style>
    .row .right-sidebar {
      display: none;
    }
  </style>

  <script>
    function setHooligan() {
      $.ajax({
        type: 'POST',
        url: '{{ route('set-hooligan') }}',
        data: {
          _token: "{{ csrf_token() }}",
          member_id: '{{ $item->getSource()->member_id ?? '' }}',
          report_id: '{{ $item->id }}',
          reporter_id: '{{ $item->member_id }}',
          report_date: '{{ $item->created_at }}',
          report_type: '{{ $item->type_report }}',
        },
        dataType: 'json',
        success: function (data) {
          if (data.error === false) {
            Botble.showNotice('success', data.message);
            location.reload();
          }
        }
      });
    }
  </script>
</div>
