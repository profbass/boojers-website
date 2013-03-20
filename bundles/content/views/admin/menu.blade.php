@layout('admin::layouts.main')

@section('page_title')
| Content
@endsection

@section('content')	
<div class="row-fluid">
    <div class="span12">
 		<h3>Create A New Page</h3>
    	<?=Form::open($controller_alias . '/store', null, array('class' => 'form-inline') ); ?>
			
			<?=Form::text('page_name', Input::old('page_name'), array('class' => 'input-large', 'requireds' => 'required', 'placeholder' => 'Page Name'));?>
			<?php
				$valid_names = array('0' => '--- New Top Level Menu Item ---');
				if (isset($menu_items) && !empty($menu_items)) {
					foreach ($menu_items as $section) {
						$valid_names[$section->id] = 'Place Under :: ' . $section->pretty_name;
					}
				}
				echo Form::select('parent_id', $valid_names, Input::old('parent_id'));
			?>

			<?=Form::select('page_type', array('cms' => 'Content Page', 'link' => 'Link', 'controller' => 'Controller'), Input::old('page_type'));?>

			<input type="submit" name="Create" value="Create Page" class="btn btn-success">
			<?=Form::token(); ?>
		<?=Form::close(); ?>	   
    	<hr>
    	<h3>Your Pages</h3>
		<?php
			$top_level_names = array('0' => 'Move To...');
			if (isset($menu_items) && !empty($menu_items)) {
				foreach ($menu_items as $section) {
					$top_level_names[$section->id] = $section->pretty_name;
				}
			}	
		?>
 		<ul class="unstyled sortable_list">
	        @if (isset($menu_items) && !empty($menu_items))
	        	<?php $a = 1; ?>
	        	@foreach ($menu_items as $section)
					<li class="level-one" data-menuid="<?=$section->id; ?>">
						<div class="row-fluid">
							<div class="span2">
								<span class="menu_pos"><?=$a; ?></span>
								<?php if ($section->display == 1): ?>
									<i class="icon-ok" title="visible"></i>
								<?php else: ?>
									<i class="icon-ban-circle" title="hidden"></i>
								<?php endif; ?>
							</div>
							<div class="span2">
								<?=$section->pretty_name; ?>
								<? if ($section->protected == 1): ?>
									<span class="label label-important">protected</span>
								<? endif; ?>
							</div>
							<div class="span3">
								<div class="btn-toolbar">
									<div class="btn-group">
										<a href="<?=$controller_alias; ?>/edit/<?=$section->id; ?>" class="btn btn-mini btn-primary"><i class="icon-pencil icon-white"></i> Edit Page</a>
										<?php if ($section->display == 0): ?>
											<a href="<?=$controller_alias; ?>/show_page/<?=$section->id; ?>" class="btn btn-mini btn-success"><i class="icon-ok icon-white"></i> Show Page</a>
										<?php else: ?>
											<a href="<?=$controller_alias; ?>/hide_page/<?=$section->id; ?>" class="btn btn-mini btn-warning"><i class="icon-ban-circle icon-white"></i> Hide Page</a>
										<?php endif; ?>
										<?php if ($section->controller == 'cms' || $section->controller == 'link'): ?>
											<a data-action="confirm" href="<?=$controller_alias; ?>/destroy/<?=$section->id; ?>" class="btn btn-mini btn-danger"><i class="icon-remove icon-white"></i> DELETE Page</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<div class="span3">
								<button class="btn btn-mini toggle-move-form pull-left" style="margin-right: 10px;">Move Item</button>
								<?=Form::open($controller_alias . '/move_page', null, array('class' => 'form-inline', 'style' => 'display:none; margin: 0') ); ?>
									<?=Form::hidden('child_id', $section->id); ?>
									<?=Form::hidden('old_parent_id', 0); ?>
									<? $temp = $top_level_names; unset($temp[$section->id]); ?>
									<?=Form::select('parent_id', $temp); ?>
									<input type="submit" name="Move" value="Move" class="btn btn-success">
									<?=Form::token(); ?>
					    		<?=Form::close(); ?>						    		
							</div>
							<div class="span1">
								<?php if (isset($section->children)): ?>
									<a href="#" data-list="level-1" data-target="#collapse_<?=$section->id; ?>" data-action="toggle-table"><i class="icon-minus"></i></a>
								<?php endif; ?>
							</div>
							<div class="span1">
								<i class="icon-move"></i>
							</div>
						</div>
						<?php if (isset($section->children)): ?>
							<ul id="collapse_<?=$section->id; ?>" class="unstyled sortable_list">
								<?php $b = 1; ?>
								<?php foreach($section->children as $child): ?>
									<li class="level-two" data-menuid="<?=$child->id; ?>">
										<div class="row-fluid">
											<div class="span2">
												<span class="menu_pos"><?=$a . '.' . $b; ?></span>
												<?php if ($child->display == 1): ?>
													<i class="icon-ok" title="visible"></i>
												<?php else: ?>
													<i class="icon-ban-circle" title="hidden"></i>
												<?php endif; ?>
											</div>
											<div class="span2">
												<?=$child->pretty_name; ?>
												<? if ($child->protected == 1): ?>
													<span class="label label-important">protected</span>
												<? endif; ?>
											</div>
											<div class="span3">
												<div class="btn-toolbar">
													<div class="btn-group">
														<a href="<?=$controller_alias; ?>/edit/<?=$child->id; ?>" class="btn btn-mini btn-primary"><i class="icon-pencil icon-white"></i> Edit Page</a>
														<?php if ($child->display == 0): ?>
															<a href="<?=$controller_alias; ?>/show_page/<?=$child->id; ?>" class="btn btn-mini btn-success"><i class="icon-ok icon-white"></i> Show Page</a>
														<?php else: ?>
															<a href="<?=$controller_alias; ?>/hide_page/<?=$child->id; ?>" class="btn btn-mini btn-warning"><i class="icon-ban-circle icon-white"></i> Hide Page</a>
														<?php endif; ?>
														<?php if ($child->controller == 'cms' || $child->controller == 'link'): ?>
															<a data-action="confirm" href="<?=$controller_alias; ?>/destroy/<?=$child->id; ?>" class="btn btn-mini btn-danger"><i class="icon-remove icon-white"></i> DELETE Page</a>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="span3">
												<button class="btn btn-mini toggle-move-form pull-left" style="margin-right: 10px;">Move Item</button>
												<?=Form::open($controller_alias . '/move_page', null, array('class' => 'form-inline', 'style' => 'display:none; margin: 0') ); ?>
													<?=Form::hidden('child_id', $child->id); ?>
													<?=Form::hidden('old_parent_id', $child->parent_id); ?>
													<? $temp = $top_level_names; $temp[0] = 'Top Level'; ?>
													<?=Form::select('parent_id', $temp); ?>
													<input type="submit" name="Move" value="Move" class="btn btn-success">
													<?=Form::token(); ?>
									    		<?=Form::close(); ?>	   
											</div>
											<div class="span1"></div>
											<div class="span1">
												<i class="icon-move"></i>
											</div>
										</div>
									</li>
									<?php $b++; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
					<?php $a++; ?>
				 @endforeach
	        @endif
	    </ul>
		<hr>
	</div>
</div>
@endsection


@section('scripts')
<script>
jQuery(document).ready(function($) {
	$('button.toggle-move-form').on('click', function(e) {
		e.preventDefault();
		$(this).next('form').toggle();
	});
	$('a[data-action="toggle-table"]').on('click', function(e) {
		var el, i, t;
		e.preventDefault();
		el = $(this);
		i = el.find('i');
		if (i.hasClass('icon-minus')) {
			i.removeClass('icon-minus').addClass('icon-plus');
		} else {
			i.addClass('icon-minus').removeClass('icon-plus');
		}
		t = $(el.data('target'));
		t.toggle();
	});

	$( "ul.sortable_list" ).sortable({
		handle: ".icon-move",
		placeholder: "ui-state-highlight",
		stop: function( event, ui ) {
			var a = 1, new_order = [];		
			
			$('.level-one').each(function() {
				var l = $(this);
				var b = 1;
				l.find('.menu_pos:first').html(a);
				l.find('.level-two').each(function() {
					var l = $(this);
					l.find('.menu_pos:first').html(a + '.' + b);
					b++;
				});
				a++;				
			});

			ui.item.parent('ul').children().each(function() {
				new_order.push($(this).data('menuid'));
			});

			$.ajax({
				url: '/admin/content/change_order',
				type: "POST",
				data: { ids: new_order.join(',') }
			}).done(function( msg ) {
				AdminApp.showAlert( 'Menu Item Moved.', 'success');
			});
		}
	});
});
</script>
@endsection