<style>
  #multiple-target {
    width: 100%;
  }
</style>
<?php
    $share_value = [];
    if(!empty($options['share_value'])){
        foreach ($options['share_value'] as $key => $item) {
            array_push($share_value, $item);
        }
    }
?>
<div>
  <select multiple="multiple" id="multiple-target" name="{{$name}}">
    @foreach ($options['data-value'] as $item)
      <option value='{{$item['id']}}' @if(in_array($item['id'], $share_value))  selected @endif>{{$item['name']}}</option>
    @endforeach
  </select>
</div>
