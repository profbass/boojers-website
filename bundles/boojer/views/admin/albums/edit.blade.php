@layout('admin::layouts.main')

@section('page_title')
| Albums - Edit
@endsection

@section('content')	
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
    	<h2>Editing <?=$album->name; ?></h2>
    	<hr>
		<?php echo Form::open($controller_alias . '/update_album/' . $album->id, null, array('class' => 'form-horizontal') ); ?>
			<fieldset>	
						
				<div class="control-group{{ isset($errors) && $errors->has('name') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('name', 'Name *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('name', Input::old('name') ? Input::old('name') : $album->name, array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Name'));
							?>
							@if ($errors && $errors->has('name'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

				<div class="control-group">
					<div class="control-group">
						<?=Form::label('description', 'Description', array('class' => 'control-label')); ?>
						<div class="controls">
							<?=Form::textarea('description', Input::old('description') ? Input::old('description') : $album->description, array('class' => 'span12', 'style' => 'height: 200px;', 'placeholder' => 'Enter Description'));?>
						</div>
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('visible') ? ' error' : '' }}">
					<label class="control-label">Album Visibility *</label>
					<div class="controls">
						<label class="radio">
							<?=Form::radio('visible', 1, ($album->visible == 1 ? true : false)); ?>
							Album is visible
						</label>
						<label class="radio">
							<?=Form::radio('visible', 0, ($album->visible == 0 ? true : false)); ?>
							Album is hidden
						</label>
					</div>
				</div>				

				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-success btn-large">Update</button>
					<a href="<?=$controller_alias;?>" class="btn btn-warning">Cancel</a>
				</div>
				<?php echo Form::token(); ?>
			</fieldset>
   		<?php echo Form::close(); ?>	
    </div>
</div>
@endsection