@layout('admin::layouts.main')

@section('page_title')
| Users - Edit Boojer
@endsection

@section('scripts')
<script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')	
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
    	<h2>Editing <?=$user->first_name . ' ' . $user->last_name; ?></h2>
    	<hr>
		<?php echo Form::open($controller_alias . '/update/' . $user->id, null, array('class' => 'form-horizontal') ); ?>
			<fieldset>	
						
				<div class="control-group{{ isset($errors) && $errors->has('first_name') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('first_name', 'First Name *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('first_name', Input::old('first_name') ? Input::old('first_name') : $user->first_name, array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter First Name'));
							?>
							@if ($errors && $errors->has('first_name'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('last_name') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('last_name', 'Last Name *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('last_name', Input::old('last_name') ? Input::old('last_name') : $user->last_name, array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Last Name'));
							?>
							@if ($errors && $errors->has('last_name'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>


				<div class="control-group{{ isset($errors) && $errors->has('slug') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('title', 'Title *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('title', Input::old('title') ? Input::old('title') : $user->title, array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Title'));
							?>
							@if ($errors && $errors->has('title'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

				<div class="control-group">
					<div class="control-group">
						<?=Form::label('professional_bio', 'Professional Bio', array('class' => 'control-label')); ?>
						<div class="controls">
							<?=Form::textarea('professional_bio', Input::old('professional_bio') ? Input::old('professional_bio') : $user->professional_bio, array('class' => 'span12 ckeditor', 'style' => 'height: 200px;', 'placeholder' => 'Enter Professional Bio'));?>
						</div>
					</div>
				</div>

				<div class="control-group">
					<div class="control-group">
						<?=Form::label('fun_bio', 'Fun Bio', array('class' => 'control-label')); ?>
						<div class="controls">
							<?=Form::textarea('fun_bio', Input::old('fun_bio') ? Input::old('fun_bio') : $user->fun_bio, array('class' => 'span12 ckeditor', 'style' => 'height: 200px;', 'placeholder' => 'Enter Fun Bio'));?>
						</div>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<div class="alert alert-block">For a reference on what markup to use and all the style resources visit <a href="http://www.getbootstrap.com" target="_blank">www.getbootstrap.com</a> and navigate to the <strong>Base CSS page</strong></div>
					</div>
				</div>
				
				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-success">Update</button>
				</div>
				<?php echo Form::token(); ?>
			</fieldset>
   		<?php echo Form::close(); ?>	
    </div>
</div>
@endsection