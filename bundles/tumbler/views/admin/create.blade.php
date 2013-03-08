@layout('admin::layouts.main')

@section('page_title')
| Tumbler - Create
@endsection

@section('content')	
<div class="row-fluid">
	<div class="span3">
		@include('tumbler::admin.sidenav')
	</div>
    <div class="span9">
    	<h2>Create New Tumbler Entry</h2>
    	<hr>
		<?php echo Form::open_for_files($controller_alias . '/store', null, array('class' => 'form-horizontal') ); ?>
			<fieldset>				

				<div class="control-group{{ isset($errors) && $errors->has('title') ? ' error' : '' }}">
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

                <div class="control-group{{ isset($errors) && $errors->has('type') ? ' error' : '' }}">
                    <?php echo Form::label('type', 'Type *', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?
                        $select_options = array('' => 'Choose Type');
                        if(!empty($types)) {
                            foreach($types as $key => $value) {
                                $select_options[$key] = $value;
                            }
                        }
                        echo Form::select('type', $select_options, Input::old('type'), array('class' => 'span6', 'required' => 'required'));
                        ?>
                        @if ($errors && $errors->has('type'))
                            <span class="help-inline">This field is required</span>
                        @endif
                    </div>
                </div>


                <div class="control-group{{ isset($errors) && $errors->has('photo') ? ' error' : '' }}">
                    <?=Form::label('photo', 'Photo', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?=Form::file('photo'); ?>
                        @if ($errors && $errors->has('photo'))
                            <span class="help-inline">This field is required</span>
                        @endif
                        <span class="help-inline">Image will be resized so it's width is {{ $dims['width'] }}px</span>
                    </div>
                </div>

				<div class="control-group">
					<div class="control-group">
						<?=Form::label('description', 'Description', array('class' => 'control-label')); ?>
						<div class="controls">
							<?=Form::textarea('description', Input::old('description'), array('class' => 'span12', 'style' => 'height: 200px;', 'placeholder' => 'Enter Description'));?>
						</div>
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('link') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('link', 'Link', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('link', Input::old('link'), array('class' => 'span6', 'placeholder' => 'Enter Link'));
							?>
							@if ($errors && $errors->has('link'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

				<div style="display:none;" class="control-group{{ isset($errors) && $errors->has('link2') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('link2', 'Link', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::text('link2', Input::old('link2'), array('class' => 'span6', 'placeholder' => 'Enter Link'));
							?>
							@if ($errors && $errors->has('link2'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

				<div class="control-group{{ isset($errors) && $errors->has('visible') ? ' error' : '' }}">
					<label class="control-label">Visibility *</label>
					<div class="controls">
						<label class="radio">
							<?=Form::radio('visible', 1, true); ?>
							Entry is visible
						</label>
						<label class="radio">
							<?=Form::radio('visible', 0, false); ?>
							Entry is hidden
						</label>
					</div>
				</div>		

				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-success btn-large">Create</button>
					<a href="<?=$controller_alias;?>" class="btn btn-warning">Cancel</a>
				</div>
				<?php echo Form::token(); ?>
			</fieldset>

   		<?php echo Form::close(); ?>
    </div>
</div>
@endsection