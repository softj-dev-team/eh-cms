
<?php
    $share_value = [];
    if(!empty($options['share_value'])){
        foreach ($options['share_value'] as $key => $item) {
            array_push($share_value,$item->id);
        }
    }
   
?>
<label for="contact" class="control-label required" aria-required="true">Member In Room</label>
<select multiple="multiple" id="my-select" name="{{$name}}">
    @foreach ($options['data-value'] as $item)
        <option value='{{$item->id}}' @if(in_array($item->id,$share_value))  selected @endif>{{$item->nickname}}</option>
    @endforeach
</select>

<script>
$( document ).ready(function() {
        $('#my-select').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Search...'>",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off'  placeholder='Search...'>",
        afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
    
        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
            if (e.which === 40){
            that.$selectableUl.focus();
            return false;
            }
        });
    
        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
            if (e.which == 40){
            that.$selectionUl.focus();
            return false;
            }
        });
        },
        afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
        },
        afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
        }
    });
});
</script>
