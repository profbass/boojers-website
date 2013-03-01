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
     	<h2>Photos For the Album "<?=$album->name;?>"</h2>
     	<hr>
		<?php echo Form::open_for_files($controller_alias . '/store_album_photo/' . $album->id, null, array('class' => 'form-inline') ); ?>
            <?=Form::label('photo', 'Upload Photo'); ?>
            <?=Form::file('photo'); ?>
            <button type="submit" name="submit" value="1" class="btn btn-success">Upload</button>
			<?php echo Form::token(); ?>
   		<?php echo Form::close(); ?>
   		
   		<hr>

     	<?php if(!empty($album->photos)): ?>
     		<ul class="thumbnails" id="photo-list">
	     		<?php foreach($album->photos as $item): ?>
	     			<li class="span2 photo-manager">
	     				<div class="thumbnail">
							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">Actions <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li role=menuitem><a href="#"><i class="icon-tags"></i> Tag Photo</a></li>
									<li role=menuitem><a href="#" data-target="#caption-form-<?=$item->id;?>" data-action="edit-photo-caption"><i class="icon-comment"></i> Edit Caption</a></li>
									<li role=menuitem><a href="<?=$item->path;?>" data-action="view-photo" target="_blank"><i class="icon-eye-open"></i> View</a></li>
									<li role=menuitem class="divider"></li>
									<li role=menuitem><a href="<?=$controller_alias . '/destroy_image/' . $item->id; ?>" data-action="delete-photo"><i class="icon-remove"></i> Delete Photo</a></li>
								</ul>
							</div>
	     					<img src="<?=$item->thumb_path;?>" alt="">

							<div class="hidden">
								<div id="caption-form-<?=$item->id;?>" style="min-width:400px">
									<form method="post" action="<?=$controller_alias;?>/update_photo_caption/<?=$item->id;?>">
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
	ul.on('click', 'a[data-action="delete-photo"]', function(e) {
		var el = $(this);
		e.preventDefault();
		$.post(el.attr('href'), function() {
			el.parents('li').remove();
		});
	});

	ul.on('click', 'a[data-action="edit-photo-caption"]', function(e) {
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
});
</script>


@endsection