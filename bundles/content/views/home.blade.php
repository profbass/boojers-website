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
        <div id="home-gallery" class="clearfix"></div>
        @if (!empty($page_data->cmspage->content))
            <div class="content-heading">{{ $page_data->cmspage->content }}</div>
        @endif
        <div id="boojer-home-circle">
        	<div>
				<p class="cabin title">/'bu dʒər/</p> <!-- /'bu d<sub>3</sub>&#399;r/ -->
				<p class="cabin">NOUN</p>
				<p class="cabin">1. &nbsp;a unique (and sometimes quirky) individual marked by an independent, creative spirit.</p>
				<p class="cabin">2. &nbsp;someone lucky enough to work for booj.</p>
			</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="/js/jquery.gplus_gallery.js"></script>
    <script>
        $(function () {
            pageJS.homePage();
        });
    </script>
@endsection