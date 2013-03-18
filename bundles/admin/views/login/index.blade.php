<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex,nofollow">
        <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
        <title>Admin Login</title>
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="/bundles/admin/css/jqueryui/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
        <link href="/bundles/admin/css/bootstrap.min.css" rel="stylesheet">
        <link href="/bundles/admin/css/styles.css" rel="stylesheet">
        <link href="/bundles/admin/css/bootstrap-responsive.min.css" rel="stylesheet">       
        {{ Asset::styles() }}
        @yield('styles')
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" id="admin-header">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a href="/admin" class="brand"><img src="/bundles/admin/img/admin_logo.png" alt="Admin Logo"></a>
                </div>
            </div>
        </nav>
        <div class="container-fluid" id="admin-container">            
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
			<div class="row-fluid">
			    <div class="span3" style="float:none; margin: 0 auto;">
			 		<h1>Login</h1>

					<?=Form::open(null, null, array('class' => 'form-horizontal') ); ?>
						
						<div class="control-group {{ isset($errors) && is_object($errors) && $errors->has('username') ? 'error' : '' }}">
							<?=Form::text('username', Input::old('username'), array('class' => 'span12', 'placeholder' => 'Enter Email Address', 'required' => 'required'));?>
							@if (isset($errors) && is_object($errors) && $errors->has('username'))
								<span class="help-block">This field is required</span>
							@endif
						</div>

						<div class="control-group {{ isset($errors) && is_object($errors) && $errors->has('password') ? 'error' : '' }}">
							<?=Form::password('password', array('class' => 'span12', 'placeholder' => 'Enter Password', 'required' => 'required'));?>
							@if (isset($errors) && is_object($errors) && $errors->has('password'))
								<span class="help-block">This field is required</span>
							@endif
						</div>

						<div class="control-group">
                            <div class="row-fluid">
                                <div class="span6">
                                    <label class="checkbox"><input type="checkbox" name="remember" value="1"> Remember Me</label>
                                </div>
                                <div class="span6">
                                    <input type="submit" name="Login" value="Login" class="btn btn-primary btn-large pull-right">
                                </div>
                            </div>
						</div>

						<p><a href="/admin/reset">Forgot Password</a></p>
						
                        <?=Form::token(); ?>
					
                    <?=Form::close(); ?>

				</div>
			</div>
        </div>
    </body>
</html>