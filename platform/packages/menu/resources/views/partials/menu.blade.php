<ol class="dd-list">
    @foreach ($menu_nodes as $key => $row)
        <li class="dd-item dd3-item @if ($row->related_id > 0) post-item @endif" data-type="{{ $row->type }}"
            data-related-id="{{ $row->related_id }}" data-title="{{ $row->getRelated()->name }}"
            data-class="{{ $row->css_class }}" data-id="{{ $row->id }}" data-custom-url="{{ $row->url }}"
            data-icon-font="{{ $row->icon_font }}" data-target="{{ $row->target }}">
            <div class="dd-handle dd3-handle"></div>
            <div class="dd3-content">
                <span class="text float-left" data-update="title">{{ $row->getRelated()->name }}</span>
                <span class="text float-right">{{ $row->type }}</span>
                <a href="#" title="" class="show-item-details"><i class="fa fa-angle-down"></i></a>
                <div class="clearfix"></div>
            </div>
            <div class="item-details">
                <label class="pad-bot-5">
                    <span class="text pad-top-5 dis-inline-block" data-update="title">{{ trans('packages/menu::menu.title') }}</span>
                    <input type="text" name="title" value="{{ $row->getRelated()->name }}"
                           data-old="{{ $row->getRelated()->name }}">
                </label>
                @if (!$row->related_id)
                    <label class="pad-bot-5 dis-inline-block">
                        <span class="text pad-top-5" data-update="custom-url">{{ trans('packages/menu::menu.url') }}</span>
                        <input type="text" name="custom-url" value="{{ $row->url }}" data-old="{{ $row->url }}">
                    </label>
                @endif
                <label class="pad-bot-5 dis-inline-block">
                    <span class="text pad-top-5" data-update="icon-font">{{ trans('packages/menu::menu.icon') }}</span>
                    <input type="text" name="icon-font" value="{{ $row->icon_font }}" data-old="{{ $row->icon_font }}">
                </label>
                <label class="pad-bot-10">
                    <span class="text pad-top-5 dis-inline-block">{{ trans('packages/menu::menu.css_class') }}</span>
                    <input type="text" name="class" value="{{ $row->css_class }}" data-old="{{ $row->css_class }}">
                </label>
                <label class="pad-bot-10">
                    <span class="text pad-top-5 dis-inline-block">{{ trans('packages/menu::menu.target') }}</span>
                    <div style="width: 228px; display: inline-block">
                        <div class="ui-select-wrapper">
                            <select name="target" class="ui-select" id="target" data-old="{{ $row->target }}">
                                <option value="_self" @if ($row->target == '_self') selected="selected" @endif>{{ trans('packages/menu::menu.self_open_link') }}
                                </option>
                                <option value="_blank" @if ($row->target == '_blank') selected="selected" @endif>{{ trans('packages/menu::menu.blank_open_link') }}
                                </option>
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>
                </label>
                <div class="clearfix"></div>
                <div class="text-right" style="margin-top: 5px">
                    <a href="#" class="btn btn-danger btn-remove btn-sm">{{ trans('packages/menu::menu.remove') }}</a>
                    <a href="#" class="btn btn-primary btn-cancel btn-sm">{{ trans('packages/menu::menu.cancel') }}</a>
                </div>
            </div>
            <div class="clearfix"></div>
            @if ($row->hasChild())
                {!!
                    Menu::generateMenu([
                        'slug' => $menu->slug,
                        'view' => 'packages.menu::partials.menu',
                        'parent_id' => $row->id,
                        'theme' => false,
                        'active' => false
                    ])
                !!}
            @endif
        </li>
    @endforeach
</ol>
