<?php
$currentUser = auth()->guard('member')->user();
?>

<style>
  .dot {
    height: 100px;
    width: 100px;
    background-color: white;
    border-radius: 50%;
    display: inline-block;
    border-style: solid;
    border-color: #EC1469;
    background-size: cover;
    background-position: center;
  }

  .mini-dot {
    height: 30px;
    width: 30px;
    background-color: white;
    border-radius: 50%;
    display: inline-block;
    text-align: center;
    border-style: solid;
    border-color: #EC1469;
  }

  .garden-image {
    display: flex;
    align-items: center;
  }

  .garden-list {
    display: flex;
    align-items: center;
  }

  .name-room {
    width: 100px;
    background-color: #F4F4F4;
    padding: 8px 0;
    text-align: center;
    margin-top: 10px;
    font-size: 12px;
  }

  .name-room span {
    white-space: pre-wrap;
  }

  .room {
    margin-right: 20px;
  }

  .group-garden-list {
    display: flex;
    margin-left: 20px;
    margin-bottom: 20px;
  }

  .room_name {
    color: #EC1469;
  }

  .group-boder-garden-list {
    border-color: #EC1469;
    border-width: 2px;
    border-style: solid;
  }

  .tbl-list-member td, .tbl-list-member th {
    padding: 10px;
  }

  .custom-control-input:checked ~ .custom-control-label::before {
    color: #fff;
    border-color: #ec1569;
    background-color: #ec1569;
  }
</style>

<div class="group-boder-garden-list"
     style="background-image: url('{{get_image_url($room->cover)}}');background-size: cover;">
  <div style="margin: 15px">
    <h5 style=" text-align: right;color: white;">안녕하세요</h5>
  </div>
  @if (isset($room))
    <div class="group-garden-list">
      <div class="garden-image" style="max-width: 110px;">
        <a href="{{route('egardenFE.room.detail',['id'=>$room->id])}}" title="{{$room->name}}">
          <div class="room">
            <div class="dot" style="background-image: url('{{ get_image_url($room->images, 'thumb') }}')">
              @if( getStatusDateByDate($room->published) == "Today" )
                <div class="mini-dot">
                  <div style="color: #EC1469; margin-top: 3px;">N</div>
                </div>
              @endif
            </div>
          </div>
        </a>
      </div>
      <div class="garden-list">
        <div class="room">
          <div class="room_name">
            <h5>{{$room->name}}</h5>
          </div>

          <div class="room-description">
            <span style="color: white">{{$room->description}}</span>
          </div>
        </div>

        <div class="transfer-buttons">
          @if( $currentUser->id === $room->member_id )
            <button class="btn btn-primary" onclick="transferToAdmin()">화원장 위임신청</button>
            <button class="btn btn-primary" data-toggle="modal" data-target="#listMember">화원장 위임하기</button>
          @elseif ($room->status == '화원장 신청 가능')
            <button class="btn btn-primary" onclick="requestOwnership()">화원장 지원</button>
          @endif

        </div>
      </div>
    </div>
  @endif
</div>

<!-- Modal -->
<div class="modal fade" id="listMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form>
          <table class="tbl-list-member">
            <thead>
            <tr>
              <th>선택</th>
              <th>사용자 계정</th>
              <th>레벨</th>
              <th>정지기록</th>
            </tr>
            </thead>
            <tbody>
            @foreach($listUserCanGetOwnership as $index => $user)
              <tr>
                <td>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="chooseUser{{ $index }}" name="user-chosen"
                           class="custom-control-input" value="{{ $user->id }}">
                    <label class="custom-control-label" for="chooseUser{{ $index }}">&nbsp;</label>
                  </div>
                </td>
                <td>{{ $user->nickname }}</td>
                <td>{{ getLevelMember($user) }}</td>
                <td>없음</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="transferToOtherUser()">저장</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
      </div>
    </div>
  </div>
</div>

<script>
  function transferToAdmin() {
    $.ajax({
      type: 'POST',
      url: '{{ route('egardenFE.room.transfer-to-admin') }}',
      data: {
        _token: "{{ csrf_token() }}",
        room_id: '{{ $room->id }}',
      },
      dataType: 'json',
      success: function (data) {
        if (data.error === false) {
          location.href = '{{ route('egardenFE.home') }}';
        }
      }
    });
  }

  function transferToOtherUser() {
    let memberId = $('input[name="user-chosen"]:checked').val();

    if (!memberId) {
      return alert('Please choose member');
    }

    $.ajax({
      type: 'POST',
      url: '{{ route('egardenFE.room.transfer-to-other-user') }}',
      data: {
        _token: "{{ csrf_token() }}",
        room_id: '{{ $room->id }}',
        member_id: memberId,
      },
      dataType: 'json',
      success: function (data) {
        if (data.error === false) {
          location.href = '{{ route('egardenFE.home') }}';
        }
      }
    });
  }

  function requestOwnership() {
    $.ajax({
      type: 'POST',
      url: '{{ route('egardenFE.room.request-ownership') }}',
      data: {
        _token: "{{ csrf_token() }}",
        room_id: '{{ $room->id }}',
        member_id: {{ $currentUser->id }},
      },
      dataType: 'json',
      success: function (data) {
        if (data.error === false) {
          location.href = '{{ route('egardenFE.home') }}';
        }
      }
    });
  }
</script>
