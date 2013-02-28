@layout('admin::layouts.main')

@section('page_title')
| Users - Create New Boojer
@endsection

@section('content')	
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
    	<h2>Create New Boojer</h2>
    	<hr>
		<?php echo Form::open_for_files($controller_alias . '/store', null, array('class' => 'form-horizontal') ); ?>
			<fieldset>				

				<div class="control-group{{ isset($errors) && $errors->has('first_name') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('first_name', 'First Name *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('first_name', Input::old('first_name'), array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter First Name'));
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
								echo Form::text('last_name', Input::old('last_name'), array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Last Name'));
							?>
							@if ($errors && $errors->has('last_name'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('email') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('email', 'Email *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::email('email', Input::old('email'), array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Email'));
							?>
							@if ($errors && $errors->has('email'))
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
								echo Form::text('title', Input::old('title'), array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Title'));
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
							<?=Form::textarea('professional_bio', Input::old('professional_bio'), array('class' => 'span12 ckeditor', 'style' => 'height: 200px;', 'placeholder' => 'Enter Professional Bio'));?>
						</div>
					</div>
				</div>

				<div class="control-group">
					<div class="control-group">
						<?=Form::label('fun_bio', 'Fun Bio', array('class' => 'control-label')); ?>
						<div class="controls">
							<?=Form::textarea('fun_bio', Input::old('fun_bio'), array('class' => 'span12 ckeditor', 'style' => 'height: 200px;', 'placeholder' => 'Enter Fun Bio'));?>
						</div>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<div class="alert alert-block">For a reference on what markup to use and all the style resources visit <a href="http://www.getbootstrap.com" target="_blank">www.getbootstrap.com</a> and navigate to the <strong>Base CSS page</strong></div>
					</div>
				</div>

                <div class="control-group{{ isset($errors) && $errors->has('professional_photo') ? ' error' : '' }}">
                    <?=Form::label('professional_photo', 'Professional Photo', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?=Form::file('professional_photo'); ?>
                        @if ($errors && $errors->has('professional_photo'))
                            <span class="help-inline">This field is required</span>
                        @endif
                    </div>
                </div>

                <div class="control-group{{ isset($errors) && $errors->has('fun_photo') ? ' error' : '' }}">
                    <?=Form::label('fun_photo', 'Fun Photo', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?=Form::file('fun_photo'); ?>
                        @if ($errors && $errors->has('fun_photo'))
                            <span class="help-inline">This field is required</span>
                        @endif
                    </div>
                </div>

                <div class="control-group{{ isset($errors) && $errors->has('tags[]') ? ' error' : '' }}">
                    <?php echo Form::label('tagsfake', 'Tags', array('class' => 'control-label')); ?>
                    <div class="controls">
                    	<ul id="tagList" class="inline">
	                        <?
	                        if (!empty($user->tags)) {
	                        	foreach ($user->tags as $t) {
	                        		echo '<li><input type="hidden" name="tags[]" value="' . $t->id . '"><span class="label label-info">' . $t->name . ' <i class="icon-remove cursor"></i></span></li>';
	                        	}
	                        }
	                        ?>
                    	</ul>
                    	<?
                        $select_options = array('' => 'Choose Tags');
                        if(!empty($tags)) {
                            foreach($tags as $obj) {
                                $select_options[$obj->id] = $obj->name;
                            }
                        }
                        echo Form::select('tagsfake', $select_options, null, array('class' => 'span6 addToList'));
                        ?>
                        @if ($errors && $errors->has('tags[]'))
                            <span class="help-inline">This field is required</span>
                        @endif
                    </div>
                </div>

				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-success">Create</button>
				</div>
				<?php echo Form::token(); ?>
			</fieldset>

   		<?php echo Form::close(); ?>
    </div>
</div>
@endsection

@section('scripts')
<script src="/ckeditor/ckeditor.js"></script>
<script>
jQuery(document).ready(function($) {
	var list = $('#tagList');
	var slist = $('select.addToList');
	slist.on('change', function() {
		var el = $(this);
		var t = $('option[value="' + el.val() + '"]', el);
		var v = el.val();
		if (v) {
			el.val('');
			t.prop('disabled', true);
			list.append('<li><input type="hidden" name="tags[]" value="' + v + '"><span class="label label-info">' + t.text() + ' <i class="icon-remove cursor"></i></span></li>');
		}
	});
	list.on('click', '.icon-remove', function() {
		var p = $(this).parents('li');
		var v = p.find('input').val();
		$('option[value="' + v + '"]', slist).prop('disabled', false);
		p.remove();
	});
	list.find('input').each(function() {
		var v = $(this).val();
		$('option[value="' + v + '"]', slist).prop('disabled', true);
	});
})
</script>
@endsection