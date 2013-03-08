@layout('layouts.main')

@section('page_title')
@if (!empty($page_data->meta_title))
| {{ $page_data->meta_title }}
@elseif (!empty($page_data->pretty_name))
| {{ $page_data->pretty_name }}
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
	<div class="container-fluid">
		<div class="row-fluid">
		    <div class="span12">
		    	<h1 class="large-heading">contact us</h1>
		    	<?php 
		    		$success_message = Session::get('success_message');
			    	if ($success_message) {
			    		echo '<div class="alert alert-block alert-success">' . $success_message . '</div>';
			    	}
		    	?>
		    	<div class="row-fluid margin-top-30">
		    		<div class="span9">
				    	<?=Form::open(null, null, array('class' => 'form-horizontal booj-contact-form') ); ?>
							<div class="control-group {{ isset($errors) && $errors->has('full_name') ? 'error' : '' }}">
								<?=Form::label('full_name', 'Full Name', array('class' => 'control-label')); ?>
								<div class="controls">
									<?=Form::text('full_name', Input::old('full_name'), array('class' => 'span12', 'placeholder' => 'enter your full name', 'required' => 'required'));	?>
									@if ($errors && $errors->has('full_name'))
										<span class="help-block">This field is required</span>
									@endif
								</div>
							</div>

							<div class="control-group">
								<?=Form::label('phone', 'Phone', array('class' => 'control-label')); ?>
								<div class="controls">
									<?=Form::text('phone', Input::old('phone'), array('class' => 'span12', 'placeholder' => 'enter your phone number')); ?>
								</div>
							</div>

							<div class="control-group {{ isset($errors) && $errors->has('email') ? 'error' : '' }}">
								<?=Form::label('email', 'E-Mail', array('class' => 'control-label')); ?>
								<div class="controls">
									<?=Form::email('email', Input::old('email'), array('class' => 'span12', 'placeholder' => 'enter your email address', 'required' => 'required')); ?>
									@if ($errors && $errors->has('email'))
										<span class="help-block">This field is required</span>
									@endif
								</div>
							</div>

							<div class="control-group {{ isset($errors) && $errors->has('message') ? 'error' : '' }}">
								<?=Form::label('message', 'Message', array('class' => 'control-label')); ?>
								<div class="controls">
									<?=Form::textarea('message', Input::old('message'), array('class' => 'span12', 'placeholder' => 'enter message', 'required' => 'required')); ?>
									@if ($errors && $errors->has('message'))
										<span class="help-block">This field is required</span>
									@endif
								</div>
							</div>

							<div class="control-group">
								<div class="controls">
									<input type="submit" name="Send" value="send" class="btn btn-primary">
								</div>
							</div>

							<?=Form::token(); ?>
						<?=Form::close(); ?>
					</div>
					<div class="span3">
						<h2 style="margin-top:-7px;">booj</h2>
						<address>
							575 union boulevard, suite 310<br>lakewood, colorado 80228
						</address>
						<ul class="unstyled contact-address-list margin-bottom-30">
							<li><span>national</span> <a href="tel://855-563-1925">855-563-1925</a></li>
							<li><span>local</span> <a href="tel://303-396-0104">303-396-0104</a></li>
							<li><span>fax</span> <a href="tel://303-479-8333">303-479-8333</a></li>
							<li><span>email</span> <a href="mailto:info@booj.com">info@booj.com</a></li>
						</ul>
						<img src="/img/map.jpg" class="gray-border" alt="Google Map Of Booj Headquarters">
					</div>
				</div>
		    </div>
		</div>
	</div>
</div>
@include('layouts.footer')

@endsection