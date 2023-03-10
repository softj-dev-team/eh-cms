
<?php
    $share_value = [];
    if(!empty($options['share_value'])){
        foreach ($options['share_value'] as $key => $item) {
            array_push($share_value,$item->id);
        }
    }

?>
<div>
  <label for="contact" class="control-label required" aria-required="true">회원과 공유</label>
  <select multiple="multiple" id="my-select" name="{{$name}}">
    @foreach ($options['data-value'] as $item)
      <option value='{{$item->id}}' @if(in_array($item->id,$share_value))  selected @endif>{{$item->nickname}}</option>
    @endforeach
  </select>
</div>
