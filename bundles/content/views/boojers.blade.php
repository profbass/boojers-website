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
    <div class="span12 relative" id="boojers-page">
        <h1 class="large-heading">Welcome! Meet the boojers</h1>
        @if (!empty($page_data->cmspage->content))
            {{ $page_data->cmspage->content }}
        @endif
        <div class="boojers-nav clearfix">
        	<button class="text-right active" data-action="toggle-tag" data-target="#pro"><span>Yea we're professionals,</span></button>
        	<button class="text-left" data-action="toggle-tag" data-target="#fun"><span>but we have a fun side too!</span></button>
        </div>
        <div id="boojer-tabs">
	        <div class="boojer-tab active" id="pro">
	        	<div class="clearfix margin-top-30 margin-bottom-30">
	        		<span class="label show-taged" data-tag="all">All</span>
	        		<? if (!empty($pro_tags)): ?>
			        	<? foreach ($pro_tags as $tag_id => $tag_name): ?>
			        		<span class="label show-taged" data-tag="<?=$tag_name;?>"><?=$tag_name;?></span>
			        	<? endforeach; ?>
			        <? endif; ?>
			    </div>
	        	<ul class="thumbnails">
	        		<? if (!empty($boojers)): ?>
			        	<? foreach ($boojers as $boojer): ?>
			        		<li class="boojer-item" data-tags="<?foreach ($boojer->tags as $tag) { if ($tag->type === 'professional') echo $tag->name . ',' ;}?>">
			        			<img src="<?=$boojer->professional_photo_small;?>" alt="">
			        			<div>
			        				<h3><?=$boojer->first_name;?> <?=$boojer->last_name;?></h3>
			        				<p><?=$boojer->title;?></p>
			        			</div>
			        		</li>
			        	<? endforeach; ?>
			        <? endif; ?>
		        </ul>
	        </div>
	        <div class="boojer-tab" id="fun">
	        	<div class="clearfix margin-top-30 margin-bottom-30">
	        		<span class="label show-taged" data-tag="all">All</span>
	        		<? if (!empty($fun_tags)): ?>
			        	<? foreach ($fun_tags as $tag_id => $tag_name): ?>
			        		<span class="label show-taged"><?=$tag_name;?></span>
			        	<? endforeach; ?>
			        <? endif; ?>
			    </div>
	        	<ul class="thumbnails">
	        		<? if (!empty($boojers)): ?>
			        	<? foreach ($boojers as $boojer): ?>
			        		<li class="boojer-item" data-tags="<?foreach ($boojer->tags as $tag) { if ($tag->type === 'fun') echo $tag->name . ',' ;}?>">
			        			<img src="<?=$boojer->fun_photo_small;?>" alt="">
			        			<div>
				        			<h3><?=$boojer->first_name;?> <?=$boojer->last_name;?></h3>
				        			<p><?=$boojer->title;?></p>
				        		</div>
			        		</li>
			        	<? endforeach; ?>
			        <? endif; ?>
		        </ul>
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


@section('scripts')
	<script>
		jQuery(document).ready(function($) {
			var scope = $('#boojers-page');
			var btns = $('[data-action="toggle-tag"]', scope);
			var tabs = $('.boojer-tab', scope);
			btns.on('click', function(e) {
				e.preventDefault();
				var el = $(this);
				btns.removeClass('active');
				tabs.removeClass('active')
				el.addClass('active');
				$(el.data('target')).addClass('active');
			});
			$('.show-taged', scope).on('click', function(e) {
				var el = $(this);
				var tag = el.data('tag');
				var currTab = $('#boojer-tabs > div.active');
				var allItems = currTab.find('.boojer-item');
				if (tag === 'all') {
					allItems.show();
				} else {
					allItems.each(function() {
						var item = $(this);
						var tags = item.data('tags').split(',');
						if ($.inArray(tag, tags) > -1) {
							item.show();
						} else {
							item.hide();
						}
					});
				}
			})
		});
	</script>
@endsection
