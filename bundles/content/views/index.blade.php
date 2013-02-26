@layout('layouts.main')

@section('page_title')
@if (!empty($page_data->meta_title))
| {{ $page_data->meta_title }}
@elseif (!empty($page_data->pretty_name))
| {{ $page_data->pretty_name }}
@endif
@endsection

@section('page_description')
@if (!empty($page_data->meta_description))
{{ $page_data->meta_description }}
@endif
@endsection

@section('page_keywords')
@if (!empty($page_data->meta_keyword))
{{ $page_data->meta_keyword }}
@endif
@endsection

@section('content')
<div class="row-fluid">
    <div class="span12">
        @if (!empty($page_data->cmspage->content))
            {{ $page_data->cmspage->content }}
        @endif
    </div>
</div>
@endsection

@if (!empty($page_data->cmspage->styles))
@section('styles')
    {{ $page_data->cmspage->styles }} 
@endsection
@endif


@if (!empty($page_data->cmspage->scripts))
@section('scripts')
    {{ $page_data->cmspage->scripts }} 
@endsection
@endif