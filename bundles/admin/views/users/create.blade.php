@layout('admin::layouts.main')

@section('page_title')
| Users - Create New User
@endsection

@section('scripts')
<script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')	
<div class="row-fluid">
	<div class="span3">
		@include('admin::users.sidenav')
	</div>
    <div class="span9">
    	<h2>Create New User</h2>
    	<hr>
		<?php echo Form::open($controller_alias . '/store', null, array('class' => 'form-horizontal') ); ?>
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

				<div class="control-group{{ isset($errors) && $errors->has('slug') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('username', 'Username *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('username', Input::old('username'), array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Username'));
							?>
							@if ($errors && $errors->has('username'))
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
								echo Form::email('email', Input::old('email'), array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter New Email Address'));
							?>
							@if ($errors && $errors->has('email'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

                <div class="control-group{{ isset($errors) && $errors->has('groups[]') ? ' error' : '' }}">
                    <?php echo Form::label('groups[]', 'User Group *', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?
                        $select_options = array('' => 'Choose User Groups');
                        if(!empty($groups)) {
                            foreach($groups as $obj) {
                                $select_options[$obj->id] = $obj->name;
                            }
                        }
                        echo Form::select('groups[]', $select_options, Input::old('groups'), array('style' => 'height:200px;', 'class' => 'span6', 'required' => 'required', 'multiple' => 'multiple'));
                        ?>
                        @if ($errors && $errors->has('groups[]'))
                            <span class="help-inline">This field is required</span>
                        @endif
                    </div>
                </div>

				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-success">Create User</button>
				</div>
				<?php echo Form::token(); ?>
			</fieldset>

   		<?php echo Form::close(); ?>
    </div>
</div>
@endsection