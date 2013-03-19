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
		    <div class="span12 relative" id="boojer-homepage">
		        <h1 class="large-heading">watercooler</h1>
	        	@if (!empty($page_data->cmspage->content))
		            <div class="content-heading">{{ $page_data->cmspage->content }}</div>
		        @endif

		        <div class="row-fluid">
		        	<? if (!empty($tumbler_chunks)): ?>
		        		<? foreach ($tumbler_chunks as $chunk): ?>
		        			<div class="span3">
		        				<? foreach ($chunk as $tumble): ?>
		        					<? if ($tumble->type == 1): ?>
		        						<div class="tumbler-item tumbler-photo clearfix">
		        							<? if (!empty($tumble->photo)): ?>
		        								<img src="<?=$tumble->photo; ?>" alt="">
		        							<? endif; ?>
		        							<div class="tumbler-caption">
			        							<i class="bicon-tumbler-camera pull-left margin-right-10"></i>
			        							<p>
			        								<? if(!empty($tumble->title)): ?>
			        									<span class="block"><?=$tumble->title;?></span>
			        								<? endif; ?>
			        								<? if(!empty($tumble->link)): ?>
			        									<a href="<?=$tumble->link;?>" target="_blank" rel="nofollow"><?=$tumble->link;?></a>
			        								<? endif; ?>
			        							</p>
			        						</div>
		        						</div>
		        					<? elseif ($tumble->type == 2): ?>
		        						<div class="tumbler-item tumbler-quote clearfix">
		        							<div class="tumbler-quote-text"><?=$tumble->description;?></div>
		        							<div class="tumbler-caption">
			        							<i class="bicon-tumbler-quote pull-left margin-right-10"></i>
			        							<p>
			        								<? if(!empty($tumble->title)): ?>
			        									<span class="block"><?=$tumble->title;?></span>
			        								<? endif; ?>
			        								<? if(!empty($tumble->link)): ?>
			        									<a href="<?=$tumble->link;?>" target="_blank" rel="nofollow"><?=$tumble->link;?></a>
			        								<? endif; ?>
			        							</p>
			        						</div>
		        						</div>
		        					<? elseif ($tumble->type == 3): ?>
		        						<div class="tumbler-item tumbler-video clearfix">
		        							<? if(!empty($tumble->link)): ?>
		        								<a href="<?=$tumble->link;?>" target="_blank" rel="nofollow"><img src="<?=$tumble->photo; ?>" alt=""></a>
		        							<? elseif (!empty($tumble->photo)): ?>
		        								<img src="<?=$tumble->photo; ?>" alt="">
		        							<? endif; ?>
		        							<div class="tumbler-caption">
			        							<i class="bicon-tumbler-video pull-left margin-right-10"></i>
			        							<p>
			        								<? if(!empty($tumble->title)): ?>
			        									<span class="block"><?=$tumble->title;?></span>
			        								<? endif; ?>
			        							</p>
			        						</div>
		        						</div>
		        					<? elseif ($tumble->type == 4): ?>
		        						<div class="tumbler-item tumbler-tweet clearfix">
		        							<div class="tumbler-tweet-text"><?=$tumble->description;?></div>
			        						<i class="bicon-tumbler-tweet pull-left margin-right-10"></i>
		        						</div>
		        					<? elseif ($tumble->type == 5): ?>
		        						<div class="tumbler-item tumbler-facebook clearfix">
		        							<? if (!empty($tumble->photo)): ?>
		        								<img src="<?=$tumble->photo; ?>" alt="">
		        							<? endif; ?>
		        							<div class="tumbler-caption">
			        							<i class="bicon-tumbler-facebook pull-left margin-right-10"></i>
			        							<p>
			        								<? if(!empty($tumble->title)): ?>
			        									<span class="block"><?=$tumble->title;?></span>
			        								<? endif; ?>
			        								<? if(!empty($tumble->link)): ?>
			        									<a href="<?=$tumble->link;?>" target="_blank" rel="nofollow"><?=$tumble->link;?></a>
			        								<? endif; ?>
			        							</p>
			        						</div>
		        						</div>
		        					<? endif; ?>

		        				<? endforeach; ?>
		        			</div>
		        		<? endforeach; ?>
		        	<? endif; ?>
		        </div>
		    </div>
		</div>
	</div>
</div>
@include('layouts.footer')
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