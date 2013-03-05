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
    <div class="span12 relative" id="boojer-homepage">
        @if (!empty($page_data->cmspage->content))
            {{ $page_data->cmspage->content }}
        @endif

        <div id="boojer-home-circle">
        	<div>
				<p class="cabin title">\bu(o)jh-er\</p>
				<p class="cabin">NOUN</p>
				<p class="cabin">1. &nbsp;An indavidual marked by an independent, creative spirit and by readiness to act.</p>
				<p class="cabin">2. &nbsp;Someone so lucky enough to  work for Booj Enterprises.</p>
			</div>
        </div>
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