@layout('layouts.main')

@section('page_title')
@if (!empty($page_data->meta_title))
| {{ strtolower($page_data->meta_title) }}
@elseif (!empty($page_data->pretty_name))
| {{ strtolower($page_data->pretty_name) }}
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
		    <div class="span12 relative" id="boojer-gallery">
		        <h1 class="large-heading">browse our photo galleries...</h1>
	        	@if (!empty($page_data->cmspage->content))
		            <div class="content-heading">{{ $page_data->cmspage->content }}</div>
		        @endif
		        @if (!empty($galleries) && !empty($galleries->results))
		        	<ul class="thumbnails">
			        	@foreach($galleries->results as $gallery)
			        		@if (!empty($gallery->photos))
			        			<li>
			        				@foreach($gallery->photos as $photo)
				        				<a href="/gallery/{{ $gallery->slug }}" title="View {{ $gallery->name }}"><img src="{{ $photo->thumb_path }}" alt="thumb"></a>
				        				<? break; ?>
			        				@endforeach
			        				<div>
				        				<h3><a href="/gallery/{{ $gallery->slug }}" title="View {{ $gallery->name }}">{{ $gallery->name }}</a></h3>
				        				<p>{{ count($gallery->photos) }} photos</p>
				        			</div>
				        			<a class="gallery-link gallery-slideshow-btn" target="_blank" href="/gallery/{{ $gallery->slug }}" data-id="{{ $gallery->id }}" title="View {{ $gallery->name }}">View Slideshow</a>
				        		</li>
		        			@endif
			        	@endforeach
			        </ul>
			        <?=$galleries->links(); ?>
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