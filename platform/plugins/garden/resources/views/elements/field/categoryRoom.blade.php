@if (!empty($options['data-value']))
<style>
.remove-categories {
    position: absolute;
    top: 0;
    right: 0;
    padding: 16px;
    color: inherit;
}
.modal-backdrop {
    z-index: 998;
}
.createCategories {
    z-index: 999;
}
</style>
<div class="btn-group">
    <div class="widget-body" style="padding: 5px 5px 5px 0;">
        <div class="btn-set">
            <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#createCategories">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>
    </div>
</div>
@foreach ($options['value'] as $item)
<div class="alert alert-success alert-dismissible" style="background-color: #{{$item->background}} ;color: #{{$item->color}}">
    <a href="javascript:void(0)" class="remove-categories" data-id="{{$item->id}}">
        <i class="fa fa-trash"></i>
    </a>
  {{$item->name}}
</div>
@endforeach

<!-- Modal -->
<div class="modal fade createCategories" tabindex="-1" role="dialog" id="createCategories">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title"><i class="til_img"></i><strong>New Categories</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body with-padding">
                <div style="margin-bottom: 10px;">
                    <div class="modal-bulk-change-content">
                        <label for="Title" class="control-label required">Title</label>
                        <input class="form-control input-value filter-column-value" placeholder="Title" autocomplete="off" type="text" id="categoriesName">
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <div class="modal-bulk-change-content">
                        <label for="Title" class="control-label required">Color</label>
                        <input class="form-control input-value filter-column-value jscolor" autocomplete="off"  type="text" id="categoriesColor">
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <div class="modal-bulk-change-content">
                        <label for="Title" class="control-label required">Background</label>
                        <input class="form-control input-value filter-column-value jscolor" autocomplete="off" type="text" id="categoriesBackground">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="float-left btn btn-warning" data-dismiss="modal">{{ trans('core/table::general.cancel') }}</button>
                <button class="float-right btn btn-info confirm-bulk-change-button" type="button">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal -->
<script>
    $(function() {

        $(document.body).on('click', '.confirm-bulk-change-button', function (e) {

                e.preventDefault();
                var $this = $(this);
                var categoriesName = $('#categoriesName').val();
                var categoriesBackground = $('#categoriesBackground').val();
                var categoriesColor = $('#categoriesColor').val();
                var idRoom = $('#idRoom').val();
                if($this.data('isRunAjax')==true){return;}
                $this.css('pointer-events', 'none').data('isRunAjax', true);
                $.ajax({
                    type:'POST',
                    url: '{{route('garden.egarden.categories.member.add')}}',
                    data:{
                            _token: "{{ csrf_token() }}",
                            'idRoom' : idRoom,
                            'categoriesName' : categoriesName,
                            'categoriesBackground' : categoriesBackground,
                            'categoriesColor' : categoriesColor,
                    },
                    dataType: 'json',
                    success: function( data ) {
                        if(data.msg == true) {
                            location.reload();
                        }
                    }
                }).always(function(){
                    $this.css('pointer-events', 'auto').data('isRunAjax', false);
                });
        });

        $(document.body).on('click', '.remove-categories', function (e) {

                var $this = $(this)
                var id = $this.attr('data-id');
                var idRoom = $('#idRoom').val();

                if($this.data('isRunAjax')==true){return;}
                $this.css('pointer-events', 'none').data('isRunAjax', true);
                $.ajax({
                    type:'POST',
                    url: '{{route('garden.egarden.categories.member.remove')}}',
                    data:{
                            _token: "{{ csrf_token() }}",
                            'id' : id,
                            'idRoom' : idRoom,
                    },
                    dataType: 'json',
                    success: function( data ) {
                        if(data.msg == true) {
                            location.reload();
                        }
                    }
                }).always(function(){
                    $this.css('pointer-events', 'auto').data('isRunAjax', false);
                });
        });
    })
</script>

@else
<script>
    $('.categoryRoom').parent().parent().parent().css('display', 'none');
</script>
@endif
