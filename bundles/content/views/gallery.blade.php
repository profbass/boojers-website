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
<div class="main-container">
	<div class="container-fluid">
		<div class="row-fluid">
		    <div class="span12 relative" id="boojer-gallery">
		        <h1 class="large-heading">Browse our photo galleries...</h1>
	        	@if (!empty($page_data->cmspage->content))
		            <div class="content-heading">{{ $page_data->cmspage->content }}</div>
		        @endif
		        @if (!empty($galleries) && !empty($galleries->results))
		        	<ul class="thumbnails">
			        	@foreach($galleries->results as $gallery)
			        		<li class="span2">
				        		@if (!empty($gallery->photos))
			        				@foreach($gallery->photos as $photo)
				        				<a class="gallery-link" target="_blank" href="/gallery/{{ $gallery->slug }}" data-id="{{ $gallery->id }}"><img src="{{ $photo->thumb_path }}" alt="thumb"></a>
				        				<? break; ?>
			        				@endforeach
			        				<div>
				        				<h3><a class="gallery-link" target="_blank" href="/gallery/{{ $gallery->slug }}" data-id="{{ $gallery->id }}">{{ $gallery->name }}</a></h3>
				        				<p>{{ count($gallery->photos) }} photos</p>
				        			</div>
			        			@else
			        				<img src="/img/no-photo.gif" alt="thumb">
			        				<div>
				        				<h3>{{ $gallery->name }}</h3>
				        				<p>No Photos Added</p>
				        			</div>
			        			@endif
			        		</li>
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
	<div class="hidden">
		<div id="gallery-viewer">
			<p class="text-center margin-top-30"><img src="/img/ajax-loader.gif" alt=""></p>
		</div>
	</div>
    <script>
		$(function () {
			pageJS.galleryPage();
		});
    </script>
@endsection