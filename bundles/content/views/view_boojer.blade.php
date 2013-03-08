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
		<div class="container-fluid" id="boojer-bio-container">
			<div class="margin-bottom-30 margin-top-30">
				<a href="/boojers" data-action="close-bio" title="Back"><img src="/img/back-arrow.png" alt="back"></a>
			</div>
			<div class="row-fluid">
				<div class="span3">
					<? if (!empty($boojer->professional_photo)):?>
						<img src="<?=$boojer->professional_photo;?>" alt="" class="bio-toggle main-photo bio-work">
					<? endif; ?>
					<? if (!empty($boojer->fun_photo)):?>
						<img src="<?=$boojer->fun_photo;?>" alt="" class="bio-toggle bio-life main-photo" style="display:none;">
					<? endif; ?>
				</div>
				<div class="span9 bio-section">
					<div class="clearfix">
						<div class="boojer-bio-section-nav pull-left margin-right-20">
							<a href="#" data-action="toggle-bio-info" data-target=".bio-life">Life</a>
							<a href="#" class="active" data-action="toggle-bio-info" data-target=".bio-work">Work</a>
						</div>
						<h2 class="pull-left"><?=$boojer->first_name;?> <?=$boojer->last_name;?></h2>
					</div>
					<p>
						<? if (!empty($boojer->title)): ?>
							<span class="bio-title"><?=$boojer->title;?></span>
						<? endif; ?>
						<? if (!empty($boojer->twitter_handle)): ?>
							<a class="bio-twitter" href="https://twitter.com/<?=$boojer->twitter_handle;?>" target="_blank" title="My Twitter Page">@<?=$boojer->twitter_handle;?></a>
						<? endif; ?>
					</p>
					<hr>
					<div class="boojer-bio">
						<div class="bio-toggle bio-work">
							<?=$boojer->professional_bio; ?>
							<? if (!empty($boojer->tags)): ?>
								<ul class="unstyled bio-tag-list margin-top-30">
									<? foreach($boojer->tags as $tag): ?>
										<? if ($tag->type === 'professional'): ?>
								        	<li><span class="<?=strtolower(str_replace(' ', '-', $tag->name));?>-label"><?=$tag->name;?></span></li>
								        <? endif; ?>
							        <? endforeach; ?>
							    </ul>
						    <? endif; ?>
						</div>
						<div class="bio-toggle bio-life" style="display:none;">
							<?=$boojer->fun_bio; ?>
							<? if (!empty($boojer->tags)): ?>
								<ul class="unstyled bio-tag-list margin-top-30">
									<? foreach($boojer->tags as $tag): ?>
										<? if ($tag->type === 'fun'): ?>
								        	<li><span class="<?=strtolower(str_replace(' ', '-', $tag->name));?>-label"><?=$tag->name;?></span></li>
								        <? endif; ?>
							        <? endforeach; ?>
							    </ul>
						    <? endif; ?>
						</div>
					</div>
				</div>
			</div>
			<? if (!empty($boojer->photos)): ?>
				<div class="bio-photos margin-top-30">
					<h3 class="upper">Photos of <?=$boojer->first_name;?></h3>
					<ul class="thumbnails">
						<? foreach($boojer->photos as $photo): ?>
							<li><a href="<?=$photo->path; ?>" rel="my-gallery" class="fancybox" target="_blank"><img src="<?=$photo->thumb_path; ?>" alt=""></a></li>
						<? endforeach; ?>
					</ul>
				</div>
			<? endif; ?>
			<div class="margin-bottom-30<? if (empty($boojer->photos)) echo ' margin-top-30'; ?>">
				<a href="/boojers" data-action="close-bio" title="Back"><img src="/img/back-arrow.png" alt="back"></a>
			</div>
		</div>
	</div>
	@include('layouts.footer')
@endsection

@section('scripts')
	<script>
		$(function () {
			pageJS.boojerBioPage();
		});
	</script>
@endsection