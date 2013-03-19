@layout('layouts.main')

@section('page_title')
@if (!empty($page_data->meta_title))
| {{ strtolower($page_data->meta_title) }}
@elseif (!empty($page_data->pretty_name))
| {{ strtolower($page_data->pretty_name) }}
@endif
@endsection

@section('page_description')
@if (!empty($page_data->meta_description))
{{ $page_data->meta_description }}
@endif
@endsection

@section('page_keywords')
@if (!empty($page_data->meta_keyword))
{{ $page_data->meta_keyword }}
@endif
@endsection

@section('content')
<div class="main-container">
	<div class="container">
		<div class="row-fluid">
		    <div class="span12">

	            <?php 
	                $success_message = Session::get('success_message');
	                if ($success_message) {
	                    echo '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">×</button>' . $success_message . '</div>';
	                }
	                $error_message = Session::get('error_message');
	                if ($error_message) {
	                    echo '<div class="alert alert-block alert-error"><button type="button" class="close" data-dismiss="alert">×</button><h4>Error!</h4>' . $error_message . '</div>';
	                }

	                if (isset($errors) && is_object($errors)) {
	                    $validator_messages = $errors->all('<div class="alert alert-block alert-error"><button type="button" class="close" data-dismiss="alert">×</button><h4>Error!</h4>:message</div>');
	                    if ($validator_messages) {
	                        foreach($validator_messages as $validator_message) {
	                            echo $validator_message;
	                        }
	                    }
	                }
	            ?>
		    	
		    	<h1 class="black-header">Login</h1>
		    	<p>This page requires that you login.</p>
				<?=Form::open('/page_login', null, array('class' => 'form-horizontal')); ?>
					<fieldset>
						<?=Form::hidden('id', $page_data->id); ?>
						
						<div class="control-group{{ isset($errors) && $errors->has('password') ? ' error' : '' }}">
							<?=Form::label('password', 'Password', array('class' => 'control-label')); ?>
							<div class="controls">
								<?=Form::text('password', Input::old('meta_title'), array('class' => 'span6', 'placeholder' => 'Enter Password'));?>
								@if ($errors && $errors->has('password'))
									<span class="help-inline">This field is required</span>
								@endif
							</div>
						</div>

						<div class="form-actions">
							<button type="submit" name="submit" value="1" class="btn btn-large btn-success">Login</button>
						</div>
						
						<?=Form::token(); ?>
					</fieldset>
		   		<?=Form::close(); ?>

		    </div>
		</div>
	</div>
</div>
@include('layouts.footer')
@endsection

@if (!empty($page_data->cmspage->styles))
@section('styles')
    {{ $page_data->cmspage->styles }} 
@endsection
@endif


@if (!empty($page_data->cmspage->scripts))
@section('scripts')
    {{ $page_data->cmspage->scripts }} 
@endsection
@endif