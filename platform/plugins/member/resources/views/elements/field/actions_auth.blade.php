<div class="table-actions">
  <div class="action-group">
    <form action={{$route_aprroval}} method="post">
      <a href="javascript:;"  onclick="parentNode.submit();"
         class="btn btn-icon btn-sm btn-primary tip" title="새내기 승인">
        <i class="far fa-check-circle"></i>
      </a>
    </form>

    <form action={{$route_deny}} method="post"  id="submit_{{$item->id}}">
      <input type="hidden" name="reason_reject_{{$item->id}}" id="reason_reject_{{$item->id}}" value="" />
      <button id="submit_deny_{{$item->id}}"
         class="btn btn-icon btn-sm btn-danger tip" title="새내기 거부">
        <i class="far fa-times-circle"></i>
      </button>
    </form>
  </div>
  <input style="text-align: center" type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" name="reason_reject_{{$item->id}}_1"
         value="@if($type =='ewhaian'){{ $item->reason_reject_2 }}@else{{ $item->reason_reject_1 }}@endif" placeholder="거부 사유">
</div>
<style>
  .action-group{
    display: flex!important;
  }
</style>

<script>
    $(document).ready(function(){
        $('#submit_deny_{{$item->id}}').click(function(){
            let reason = $('input[name="reason_reject_{{$item->id}}_1"]').val();
            $("#reason_reject_{{$item->id}}").val(reason);
            $('submit_{{$item->id}}').submit();
        });
    });
</script>
