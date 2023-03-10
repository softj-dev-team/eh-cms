<span class="openPolicy" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="cursor: pointer">
  <span class="required">*</span>
  {{__('campus.study_room.view_bulletin_board_policy')}}
</span>
@php
$route = Route::currentRouteName();
$title = null;

switch ($route){
    case "openSpaceFE.create":
    case "openSpaceFE.edit":
        $title = '열린광장';
        $name = 'policy_life_open_space';
        break;
    case "flareMarketFE.create":
        $title = '벼룩시장';
        $name = 'policy_life_flare';
        break;
    case "jobsPartTimeFE.create":
        $title = '알바하자';
        $name = 'policy_life_jobs_part_time';
        break;
    case "shelterFE.create":
    case "shelterFE.edit":
        $title = '주거정보';
        $name = 'policy_life_shelter';
        break;
    case "adsFE.create":
    case "adsFE.edit":
        $title = '광고홍보';
        $name = 'policy_life_advertisements';
        break;
    case "studyRoomFE.create":
    case "studyRoomFE.edit":
        $title = 'campus_study_room';
        $name = 'policy_campus_study_room';
        break;
    case "genealogyFE.create":
    case "genealogyFE.edit":
        $title = 'campus_genealogy';
        $name = 'policy_campus_genealogy';
        break;
    default:
        $name = 'policy';
}

$content = \Botble\Page\Models\Page::where(['name' => $name])->pluck('content');

@endphp

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!!$content[0]!!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>
<style>
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}
</style>
