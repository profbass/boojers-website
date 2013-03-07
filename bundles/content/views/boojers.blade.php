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
		    <div class="span12 relative" id="boojers-page">
		        <h1 class="large-heading">Welcome! Meet the boojers</h1>
	        	@if (!empty($page_data->cmspage->content))
		            <div class="content-heading">{{ $page_data->cmspage->content }}</div>
		        @endif
		        <div class="boojers-nav clearfix">
		        	<button class="text-right active" data-action="toggle-tag" data-target="#pro"><span>Yea we're professionals,</span></button>
		        	<button class="text-left" data-action="toggle-tag" data-target="#fun"><span>but we have a fun side, too.</span></button>
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
	</div>
</div>
@include('layouts.footer')
@endsection

@section('scripts')
	<script>
		var pros = [], fun = [];
		<? if (!empty($boojers)): ?>
		<? foreach ($boojers as $boojer): ?>
		pros.push({'tags': '<?foreach ($boojer->tags as $tag) { if ($tag->type === "professional") echo strtolower(str_replace(' ', '-', $tag->name)) . ',' ;}?>', 'html': '<a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><img src="<?=$boojer->professional_photo_small;?>" alt="photo of <?=$boojer->first_name;?> <?=$boojer->last_name;?>"></a><div><h3><a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><?=$boojer->first_name;?> <?=$boojer->last_name;?></a></h3><p><?=$boojer->title;?></p></div>'});
		fun.push({'tags': '<?foreach ($boojer->tags as $tag) { if ($tag->type === "fun") echo strtolower(str_replace(' ', '-', $tag->name)) . ',' ;}?>', 'html': '<a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><img src="<?=$boojer->fun_photo_small;?>" alt="photo of <?=$boojer->first_name;?> <?=$boojer->last_name;?>"></a><div><h3><a href="/boojers/<?=$boojer->id;?>" data-action="get-bio" target="_blank"><?=$boojer->first_name;?> <?=$boojer->last_name;?></a></h3><p><?=$boojer->title;?></p></div>'});
		<? endforeach; ?>
		<? endif; ?>
		$(function () {
			pageJS.boojersPage();
		});
	</script>
@endsection