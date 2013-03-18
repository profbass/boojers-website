@layout('admin::layouts.main')

@section('page_title')
| Content - Edit Page Information
@endsection

@section('content')	
<div class="row-fluid">
	<div class="span3">
		@include('content::admin.sidenav')
	</div>
    <div class="span9">
     	<h2>Editing "<?=$page->pretty_name; ?>" Information</h2>

		<ul class="nav nav-tabs">
			<li class="active"><a>Edit Page Information</a></li>
			<?php if (!empty($page->cmspage)): ?>
				<li>
					<a href="<?=$controller_alias;?>/edit_content/<?=$page->id; ?>/<?=$page->cmspage->id; ?>">Edit the Content Section of This Page</a>
				</li>
			<?php endif; ?>
		</ul>

		<?=Form::open($controller_alias . '/update/' . $page->id, null, array('class' => 'form-horizontal')); ?>
			<fieldset>
				
				<?=Form::hidden('id', $page->id); ?>
				<?=Form::hidden('controller', $page->controller); ?>
				
				<?php if (!empty($page->cmspage)): ?>
					<div class="control-group">
						<label class="control-label">Content Section</label>
						<div class="controls">
							<a href="<?=$controller_alias;?>/edit_content/<?=$page->id; ?>/<?=$page->cmspage->id; ?>" class="btn">Edit the Content Section of This Page</a>
						</div>
					</div>
				<?php endif; ?>
				
				<? if (in_array($page->controller, array('controller', 'home'))): ?>
					<?=Form::hidden('pretty_name', $page->pretty_name); ?>
				<? else: ?>
					<div class="control-group{{ isset($errors) && $errors->has('pretty_name') ? ' error' : '' }}">
						<?=Form::label('pretty_name', 'Page Name *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?=Form::text('pretty_name', Input::old('pretty_name') ? Input::old('pretty_name') : $page->pretty_name, array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Page Name'));?>
							@if ($errors && $errors->has('pretty_name'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				<? endif; ?>

				<div class="control-group{{ isset($errors) && $errors->has('display') ? ' error' : '' }}">
					<label class="control-label">Page Visibility *</label>
					<div class="controls">
						<label class="radio">
							<?=Form::radio('display', 1, ($page->display == 1 ? true : false)); ?>
							Page is visible
						</label>
						<label class="radio">
							<?=Form::radio('display', 0, ($page->display == 0 ? true : false)); ?>
							Page is hidden
						</label>
					</div>
				</div>

				<?php if ($page->controller == 'link'): ?>
					<div class="control-group{{ isset($errors) && $errors->has('uri') ? ' error' : '' }}">
						<?=Form::label('uri', 'Page Link *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?=Form::url('uri', Input::old('uri') ? Input::old('uri') : $page->uri, array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Page Link URL'));?>
							@if ($errors && $errors->has('uri'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				<?php endif; ?>

				<div class="control-group{{ isset($errors) && $errors->has('meta_title') ? ' error' : '' }}">
					<?=Form::label('meta_title', 'Page Title', array('class' => 'control-label')); ?>
					<div class="controls">
						<?=Form::text('meta_title', Input::old('meta_title') ? Input::old('meta_title') : $page->meta_title, array('class' => 'span6', 'placeholder' => 'Enter Page Title'));?>
						<span class="help-inline"><span class="label label-info">SEO</span></span>
						@if ($errors && $errors->has('meta_title'))
							<span class="help-inline">This field is required</span>
						@endif
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('meta_keyword') ? ' error' : '' }}">
					<?=Form::label('meta_keyword', 'Page Keywords', array('class' => 'control-label')); ?>
					<div class="controls">
						<?=Form::textarea('meta_keyword', Input::old('meta_keyword') ? Input::old('meta_keyword') : $page->meta_keyword, array('class' => 'span6', 'placeholder' => 'Enter Page Keywords')); ?>
						<span class="help-inline"><span class="label label-info">SEO</span></span>
						@if ($errors && $errors->has('meta_keyword'))
							<span class="help-block">This field is required</span>
						@endif
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('meta_description') ? ' error' : '' }}">
					<?=Form::label('meta_description', 'Page Description', array('class' => 'control-label')); ?>
					<div class="controls">
						<?= Form::textarea('meta_description', Input::old('meta_description') ? Input::old('meta_description') : $page->meta_description, array('class' => 'span6', 'placeholder' => 'Enter Page Description'));?>
						<span class="help-inline"><span class="label label-info">SEO</span></span>
						@if ($errors && $errors->has('meta_description'))
							<span class="help-block">This field is required</span>
						@endif
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('protected') ? ' error' : '' }}">
					<label class="control-label">Password Protected *</label>
					<div class="controls">
						<label class="radio inline">
							<?=Form::radio('protected', 1, ($page->protected == 1 ? true : false)); ?>
							Page is password protected
						</label>
						<label class="radio inline">
							<?=Form::radio('protected', 0, ($page->protected == 0 ? true : false)); ?>
							Page is not password protected
						</label>
						<span class="help-inline"><span class="label label-info">Security</span></span>
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('password') ? ' error' : '' }}">
					<?=Form::label('password', 'Password', array('class' => 'control-label')); ?>
					<div class="controls">
						<?=Form::text('password', Input::old('password') ? Input::old('password') : $page->password, array('class' => 'span6', 'placeholder' => 'Enter Password'));?>
						<span class="help-inline"><span class="label label-info">Security</span></span>
						@if ($errors && $errors->has('password'))
							<span class="help-inline">This field is required</span>
						@endif
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-large btn-success">Save Changes</button>
				</div>
				
				<?=Form::token(); ?>
			</fieldset>
   		<?=Form::close(); ?>	   
    </div>
</div>
@endsection