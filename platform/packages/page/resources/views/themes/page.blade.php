<div class="container">
    <h3 class="page-intro__title">{{ $page->name }}</h3>
    {!! Theme::breadcrumb()->render() !!}
</div>
<div>
    {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, html_entity_decode($page->content) , $page) !!}
</div>
