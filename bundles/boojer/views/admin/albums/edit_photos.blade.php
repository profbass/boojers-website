@layout('admin::layouts.main')

@section('page_title')
| Album - Manage Photos
@endsection

@section('styles')
<link href="/bundles/admin/css/jquery.fancybox.css" rel="stylesheet" >
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
     	<a href="<?=$controller_alias;?>/edit_album/<?=$album->id;?>" class="btn pull-right btn-primary btn-large">Edit This Album</a>
     	<h2>Photos For the Album "<?=$album->name;?>"</h2>
     	<hr>
		<?php echo Form::open_for_files($controller_alias . '/store_album_photo/' . $album->id, null, array('class' => 'form-inline') ); ?>
            <?=Form::label('photo[]', 'Upload Photo'); ?>
            <?=Form::file('photo[]', array('multiple' => 'multiple')); ?>
            <button type="submit" name="submit" value="1" class="btn btn-success">Upload</button>
			<?php echo Form::token(); ?>
   		<?php echo Form::close(); ?>
   		
   		<hr>

     	<?php if(!empty($album->photos)): ?>
     		<ul class="thumbnails" id="photo-list">
	     		<?php foreach($album->photos as $item): ?>
	     			<li class="span2 photo-manager">
	     				<div class="thumbnail">
	     					<img src="<?=$item->thumb_path;?>" alt="">

							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">Actions <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li role="menuitem"><a href="#" data-target="#tag-form-<?=$item->id;?>" data-action="edit-photo-tags"><i class="icon-tags"></i> Tag Photo</a></li>
									<li role="menuitem"><a href="#" data-target="#caption-form-<?=$item->id;?>" data-action="edit-photo-caption"><i class="icon-comment"></i> Edit Caption</a></li>
									<li role="menuitem"><a href="<?=$item->path;?>" data-action="view-photo" target="_blank"><i class="icon-eye-open"></i> View</a></li>
									<li role="menuitem"><a href="<?=$controller_alias . '/vote_up_album_photo/' . $item->id; ?>" data-action="vote-photo" target="_blank"><i class="icon-thumbs-up"></i> Vote +</a></li>
									<li role="menuitem"><a href="<?=$controller_alias . '/vote_down_album_photo/' . $item->id; ?>" data-action="vote-photo" target="_blank"><i class="icon-thumbs-down"></i> Vote -</a></li>
									<li role="menuitem" class="divider"></li>
									<li role="menuitem"><a href="<?=$controller_alias . '/destroy_album_photo/' . $item->id; ?>" data-action="delete-photo"><i class="icon-remove"></i> Delete Photo</a></li>
								</ul>
							</div>
							<? if($item->album_cover == 1): ?>
								<input type="hidden" name="current_cover" value="<?=$item->id;?>">
							<? endif; ?>
							<span class="album-cover"><label class="radio"><input type="radio" name="album_cover" value="<?=$item->id;?>"<? if($item->album_cover == 1) echo ' checked="checked"'; ?>> Cover</label></span>
							<span class="vote-count"><?=$item->votes; ?> votes</span>			
							<div class="hidden">
								<div id="tag-form-<?=$item->id;?>" style="width:400px;">
									<form method="post" action="<?=$controller_alias;?>/update_album_photo_tags/<?=$item->id;?>">
										<p class="clearfix">
											<img src="<?=$item->thumb_path;?>" class="pull-left img-polaroid margin-right-15" style="max-width:68px; max-height:68px;">
											<strong>Edit Tags</strong>
										</p>
										<div class="control-group">
											<ul class="clearfix unstyled photo-tag-list">
												<? $used = array(); ?>
												<? if (!empty($item->tags)): ?>
													<? foreach ($item->tags as $tag): ?>
														<? $used[] = $tag->id; ?>
														<li class="pull-left margin-bottom-5 margin-right-5">
															<input type="hidden" name="tags[]" value="<?=$tag->id;?>">
															<span class="label label-info">
																<?=$tag->first_name . ' ' . $tag->last_name;?> 
																<i class="icon-remove cursor remove-photo-tag"></i>
															</span>
														</li>
													<? endforeach; ?>
												<? endif; ?>
											</ul>
											<select class="photo-tag-options" name="available_tags">
												<option value="">Choose Boojer</option>
												<? if (!empty($boojers)): ?>
													<? foreach ($boojers as $boojer): ?>
														<option<? if(in_array($boojer->id, $used)) echo ' disabled="disabled"';?> value="<?=$boojer->id;?>"><?=$boojer->first_name . ' ' . $boojer->last_name;?></option>
													<? endforeach; ?>
												<? endif; ?>
											</select>
										</div>
										<button class="btn btn-success">Save</button>
									</form>
								</div>
							</div>

							<div class="hidden">
								<div id="caption-form-<?=$item->id;?>" style="width:400px">
									<form method="post" action="<?=$controller_alias;?>/update_album_photo_caption/<?=$item->id;?>">
										<p class="clearfix">
											<img src="<?=$item->thumb_path;?>" class="pull-left img-polaroid margin-right-15" style="max-width:68px; max-height:68px;">
											<strong>Edit Caption</strong>
										</p>
										<div class="row-fluid">
											<div class="span12">
												<textarea class="span12" rows="4" cols="25" name="caption" placeholder="Enter Caption"><?=$item->caption;?></textarea>
											</div>
										</div>
										<button class="btn btn-success">Save</button>
									</form>
								</div>
							</div>

	     				</div>
	     			</li>
	     		<?php endforeach; ?>
	     	</ul>
	    <?php else: ?>
	    	<p>No photos have been Uploaded to this album.</p>
     	<?php endif; ?>
    </div>
</div>
@endsection


@section('scripts')
<script src="/bundles/admin/js/jquery.fancybox.pack.js"></script>
<script>
jQuery(document).ready(function($) {
	var ul = $('#photo-list');
	var doc = $(document);
	ul.on('click', 'a[data-action="delete-photo"]', function(e) {
		var el = $(this);
		e.preventDefault();
		$.post(el.attr('href'), function() {
			el.parents('li').remove();
		});
	});

	$('a[data-action="view-photo"]', ul).fancybox();

	ul.on('click', 'a[data-action="edit-photo-caption"],a[data-action="edit-photo-tags"]', function(e) {
		var el = $(this);
		e.preventDefault();
		$.fancybox($(el.data('target')), {
			afterLoad: function(a,b) {
				var f = a.content.find('form');
				f.find('button').removeClass('disabled');
				f.on('submit', function(e) {
					e.preventDefault();
					f.find('button').addClass('disabled');
					$.post(f.attr('action'), f.serialize(), function() {
						$.fancybox.close( true );
					});
				});
			}
		});
	});

	doc.on('click', 'a[data-action="vote-photo"]', function(e) {
		e.preventDefault();
		var el = $(this);
		$.post(el.attr('href'), {}, function(data) {
			el.parents('.thumbnail').find('.vote-count').html(data + ' Votes');
		});
	});

	doc.on('change', 'input[name="album_cover"]', function() {
		var el = $(this);
		var old_cover = $('input[name="current_cover"]');
		var old_value = 0;
		if (old_cover.length) {
			old_value = old_cover.val();
		}
		$.post('<?=$controller_alias;?>/update_album_cover_photo', {'old_cover': old_value, 'album_cover': el.val()}, function() {
			AdminApp.showAlert("Cover Saved", 'success');
		});
	});

	doc.on('change', '.photo-tag-options', function() {
		var el = $(this);
		var f = el.parents('.control-group');
		var t = $('option[value="' + el.val() + '"]', el);
		var v = el.val();
		if (v) {
			el.val('');
			t.prop('disabled', true);
			f.find('.photo-tag-list').append('<li class="pull-left margin-bottom-5 margin-right-5"><input type="hidden" name="tags[]" value="' + v + '"><span class="label label-info">' + t.text() + ' <i class="icon-remove cursor remove-photo-tag"></i></span></li>');
		}
	});

	doc.on('click', '.remove-photo-tag', function() {
		var p = $(this).parents('li');
		var f = p.parents('.control-group');
		var v = p.find('input').val();
		$('option[value="' + v + '"]', f).prop('disabled', false);
		p.remove();
	});
	
});
</script>


@endsection