@layout('admin::layouts.main')

@section('page_title')
| Tag - Edit
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
    	<h2>Editing <?=$tag->name; ?></h2>
    	<hr>
		<?php echo Form::open_for_files($controller_alias . '/update_tag/' . $tag->id, null, array('class' => 'form-horizontal') ); ?>
			<fieldset>	
						
				<div class="control-group{{ isset($errors) && $errors->has('name') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('name', 'Name *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('name', Input::old('name') ? Input::old('name') : $tag->name, array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter Name'));
							?>
							@if ($errors && $errors->has('name'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

                <div class="control-group{{ isset($errors) && $errors->has('type') ? ' error' : '' }}">
                    <?php echo Form::label('type', 'User Group *', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?
                        $select_options = array('' => 'Choose Type');
                        if(!empty($tag_types)) {
                            foreach($tag_types as $key => $value) {
                                $select_options[$key] = $value;
                            }
                        }
                        echo Form::select('type', $select_options, Input::old('type') ? Input::old('type') : $tag->type, array('class' => 'span6', 'required' => 'required'));
                        ?>
                        @if ($errors && $errors->has('type'))
                            <span class="help-inline">This field is required</span>
                        @endif
                    </div>
                </div>
				
				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-success btn-large">Update</button>
					<a href="<?=$controller_alias;?>/tags" class="btn btn-warning">Cancel</a>
				</div>
				<?php echo Form::token(); ?>
			</fieldset>
   		<?php echo Form::close(); ?>	
    </div>
</div>
@endsection