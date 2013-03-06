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
	        	<ul class="unstyled boojer-tag-list">
	        		<li class="pull-left"><span class="show-taged all-label active" data-type="pro" data-id="all">All</span></li>
	        		<? if (!empty($pro_tags)): ?>
			        	<? foreach ($pro_tags as $tag_id => $tag_name): ?>
			        		<li class="pull-left"><span class="show-taged <?=strtolower(str_replace(' ', '-', $tag_name));?>-label" data-type="pro" data-id="<?=strtolower(str_replace(' ', '-', $tag_name));?>"><?=$tag_name;?></span></li>
			        	<? endforeach; ?>
			        <? endif; ?>
			    </ul>
	        	<ul class="display-list thumbnails">
	        		<? if (!empty($boojers)): ?>
			        	<? foreach ($boojers as $boojer): ?>
			        		<li class="boojer-item" data-id="all">
			        			<a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><img src="<?=$boojer->professional_photo_small;?>" alt="photo of <?=$boojer->first_name;?> <?=$boojer->last_name;?>"></a>
			        			<div>
			        				<h3><a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><?=$boojer->first_name;?> <?=$boojer->last_name;?></a></h3>
			        				<p><?=$boojer->title;?></p>
			        			</div>
			        		</li>
			        	<? endforeach; ?>
			        <? endif; ?>
		        </ul>
	        </div>
	        <div class="boojer-tab" id="fun">
	        	<ul class="unstyled boojer-tag-list">
	        		<li class="pull-left"><span class="show-taged all-label active" data-type="fun" data-id="all">All</span></li>
	        		<? if (!empty($fun_tags)): ?>
			        	<? foreach ($fun_tags as $tag_id => $tag_name): ?>
			        		<li class="pull-left"><span class="show-taged <?=strtolower(str_replace(' ', '-', $tag_name));?>-label" data-type="fun" data-id="<?=strtolower(str_replace(' ', '-', $tag_name));?>"><?=$tag_name;?></span></li>
			        	<? endforeach; ?>
			        <? endif; ?>
			    </ul>
	        	<ul class="display-list thumbnails">
	        		<? if (!empty($boojers)): ?>
			        	<? foreach ($boojers as $boojer): ?>
			        		<li class="boojer-item" data-id="all">
			        			<a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><img src="<?=$boojer->fun_photo_small;?>" alt="photo of <?=$boojer->first_name;?> <?=$boojer->last_name;?>"></a>
			        			<div>
			        				<h3><a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><?=$boojer->first_name;?> <?=$boojer->last_name;?></a></h3>
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
<div id="boojer-bio" style="display:none;"></div>
@endsection

@section('styles')
<link href="/css/fancybox/jquery.fancybox.css" rel="stylesheet">
@endsection

@section('scripts')
	<script>
		var DOC = jQuery(document), pros = [], fun = [];
		
		<? if (!empty($boojers)): ?>
		<? foreach ($boojers as $boojer): ?>
		pros.push({'tags': '<?foreach ($boojer->tags as $tag) { if ($tag->type === "professional") echo strtolower(str_replace(' ', '-', $tag->name)) . ',' ;}?>', 'html': '<a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><img src="<?=$boojer->professional_photo_small;?>" alt="photo of <?=$boojer->first_name;?> <?=$boojer->last_name;?>"></a><div><h3><a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><?=$boojer->first_name;?> <?=$boojer->last_name;?></a></h3><p><?=$boojer->title;?></p></div>'});
		fun.push({'tags': '<?foreach ($boojer->tags as $tag) { if ($tag->type === "fun") echo strtolower(str_replace(' ', '-', $tag->name)) . ',' ;}?>', 'html': '<a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><img src="<?=$boojer->fun_photo_small;?>" alt="photo of <?=$boojer->first_name;?> <?=$boojer->last_name;?>"></a><div><h3><a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><?=$boojer->first_name;?> <?=$boojer->last_name;?></a></h3><p><?=$boojer->title;?></p></div>'});
		<? endforeach; ?>
		<? endif; ?>

		DOC.ready(function($) {
			var scope = $('#boojers-page');
			var btns = $('[data-action="toggle-tag"]', scope);
			var tabs = $('.boojer-tab', scope);
			var bio = $('#boojer-bio');
			var header = $('#site-header');
			var BODY = $('body');
			var tempHolder = $('<ul></ul>').appendTo(BODY);

			btns.on('click', function(e) {
				e.preventDefault();
				var el = $(this);				
				btns.removeClass('active');
				tabs.removeClass('active')
				el.addClass('active');
				$(el.data('target')).addClass('active');
				tempHolder.html('');
			});

			var hideBio = function() {
				if ("onhashchange" in window) {
					try {
						window.removeEventListener("hashchange", hideBio, false);
					} catch (e) {

					}
				}
				window.location.hash = '';
				bio.fadeOut(600, function() {
					bio.hide();
				});
			};

			$('.show-taged', scope).on('click', function (e) {
				var el = $(this);
				var currTag = el.data('id');
				var currType = el.data('type');
				var currTab = $('#' + currType);
				var sourceList = currType === 'fun' ? fun : pros;
				var i;

				e.preventDefault();
				tempHolder.html('');

				el.parents('ul').find('.active').removeClass('active');
				el.addClass('active');
				
				for (i = 0; i < sourceList.length; i++) {
					if (currTag === 'all' || $.inArray(currTag, sourceList[i].tags.substr(0, sourceList[i].tags.length - 1).split(',')) > -1) {
						tempHolder.append('<li data-id="' + currTag + '">' + sourceList[i].html + '</li>');
					}
				}

				$('.display-list', currTab).quicksand(tempHolder.find('li'), {
					adjustHeight: 'auto',
					attribute: function(v) {
						return $(v).find('img').attr('src') + currTab;
					}
          		}, function() {
					$('.display-list', currTab).css({
						width: 'auto',
						height: 'auto'
					});
				});				
			});

			DOC.on('click', 'a[data-action="get-bio"]', function(e) {
				e.preventDefault();
				window.location.hash = '#viewing-bio';
				bio.html('<div class="container-fluid"><p class="text-center">loading...</p></div>').css({
					top: header.height(),
					minHeight: BODY.height()
				}).fadeIn(600);
				$.post($(this).attr('href'), function(data) {
					bio.html(data);
					bio.find('.fancybox').fancybox();
					if ("onhashchange" in window) {
						try {
							window.addEventListener("hashchange", hideBio, false);
						} catch(e) {

						}
					}	
				});
			});

			$(window).on('resize', function() {
				bio.css({
					minHeight: BODY.height()
				});
			});

			DOC.on('click', 'a[data-action="close-bio"]', function(e) {
				e.preventDefault();
				hideBio();
			});

			DOC.on('click', '[data-action="toggle-bio-info"]', function(e) {
				e.preventDefault();
				$('.bio-toggle', bio).hide();
				$($(this).data('target'), bio).show();
			});
		});
	</script>
@endsection