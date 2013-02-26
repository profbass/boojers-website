@layout('admin::layouts.main')

@section('page_title')
| Content - Edit Sidebar
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('content::admin.sidenav')
	</div>
    <div class="span9">
    	<h2>Editing Sidebar</h2>
		<?=Form::open($action_url . 'update_sidebar/' . $cms_page->id, null, array('class' => 'form-horizontal') ); ?>
			<fieldset>
				<?=Form::hidden('id', $cms_page->id); ?>
				<?=Form::hidden('scripts', ''); ?>
				<?=Form::hidden('styles', ''); ?>

				<div class="control-group">
					<div class="row-fluid">
						<div class="span12">
						<?=Form::label('content', 'Content', array('class' => 'bold')); ?>
							<?php
								echo Form::textarea('content', Input::old('content') ? Input::old('content') : $cms_page->content, array('class' => 'span12', 'style' => 'height: 400px;', 'placeholder' => 'Enter Content'));
							?>
							@if ($errors && $errors->has('content'))
								<span class="help-block">This field is required</span>
							@endif
						</div>
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