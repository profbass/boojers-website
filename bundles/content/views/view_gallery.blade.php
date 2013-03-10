@layout('layouts.main')

@section('page_title')
@if (!empty($page_data->meta_title))
| {{ $page_data->meta_title }}
@elseif (!empty($page_data->pretty_name))
| {{ $page_data->pretty_name }}
@endif
@if (!empty($gallery->name))
 ~ {{ $gallery->name }}
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
<div class="main-container">
	<div class="container-fluid">
		<div class="row-fluid">
		    <div class="span12" id="boojer-gallery">
		        <h1 class="large-heading">{{ strtolower($gallery->name) }}</h1>
		        @if (!empty($gallery->photos))
		        	<ul class="thumbnails full-gallery-thumbnails">
			        	@foreach($gallery->photos as $photo)
			        		<li>
		        				<a class="gallery-link" target="_blank" href="#" data-id="{{ $gallery->id }}"><img src="{{ $photo->thumb_path }}" alt="thumb"></a>
			        		</li>
			        	@endforeach
			        </ul>
		        @endif
		    </div>
		</div>
	</div>
</div>
@include('layouts.footer')
@endsection

@section('scripts')
    <script>
		$(function () {
			pageJS.galleryPage();
		});
    </script>
@endsection