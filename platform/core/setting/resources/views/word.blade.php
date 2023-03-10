@extends('core.base::layouts.master')
@section('content')
    {!! Form::open(['route' => ['settings.word.edit']]) !!}
    <div class="max-width-1200">
        <div class="flexbox-annotated-section">

            <div class="flexbox-annotated-section-content">
                <div class="wrapper-content pd-all-20">

                    <div class="form-group">
                        <label class="text-title-field"
                               for="seo_description">욕설: </label>
                        <textarea data-counter="500" rows="10" class="next-input" name="word_rejects" id="word_rejects">{{$word_rejects}}</textarea>
                    </div>
                </div>
            </div>
        </div>
      <div class="flexbox-annotated-section" style="border: none">
        <div class="flexbox-annotated-section-annotation">
          &nbsp;
        </div>
        <div class="flexbox-annotated-section-content">
          <button class="btn btn-info" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
@endsection
