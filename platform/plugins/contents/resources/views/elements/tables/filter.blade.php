<style>
.page-content-wrapper  .page-content .table-wrapper .table-configuration-wrap {
  display: none;
}
</style>
<div class="wrapper-filter">
    <p>검색</p>

    <input type="hidden" class="filter-data-url" value="{{ route('tables.get-filter-input') }}">

    {{ Form::open(['method' => 'GET', 'class' => 'filter-form']) }}
        <input type="hidden" name="filter_table_id" class="filter-data-table-id" value="{{ $table_id }}">
        <input type="hidden" name="class" class="filter-data-class" value="{{ $class }}">
        <div class="filter_list inline-block filter-items-wrap">
            @foreach($request_filters as $filter_key => $filter_item)
                <div class="filter-item form-filter @if ($loop->first) filter-item-default @endif">
                    @foreach($columns as $column_key => $column)
                        <input type="hidden" value="{{ $column_key }}" name="filter_columns[]" >
                    @endforeach
                    <input type="hidden" name="filter_operators[]" value="like">
                    <span class="filter-column-value-wrap">
                        <input class="form-control filter-column-value" type="text" placeholder="{{ trans('core/table::general.value') }}"
                               name="filter_values[]" value="{{ @app('request')->input('filter_values')[0] }}">
                    </span>

                </div>
            @endforeach
        </div>
    {{ Form::close() }}
</div>
