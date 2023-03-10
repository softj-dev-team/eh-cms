@extends('core.base::errors.master')

@section('content')

    <div style="margin: 50px;">
        <div class="col-md-10">
            <h3>{{ trans('core/base::errors.404_title') }}</h3>
            <p>{{ trans('core/base::errors.reasons') }}</p>
            <ul>
                {!! trans('core/base::errors.404_msg') !!}
            </ul>

            <p>{!! trans('core/base::errors.try_again') !!}</p>
        </div>
    </div>

@stop
