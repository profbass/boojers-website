@layout('layouts.main')

@section('page_title')
| Error 500 - Internal Server Error
@endsection


@section('content')
<div class="container">
	<div class="row-fluid">
	    <div class="span12">
			<?php if (isset($messages)) echo '<h1>' . $messages[mt_rand(0, 2)] . '</h1>'; ?>

			<h2>Server Error: 500 (Internal Server Error)</h2>

			<hr>

			<p>
				Something went wrong on our servers while we were processing your request.
				We're really sorry about this, and will work hard to get this resolved as
				soon as possible.
			</p>

			<p>
				Perhaps you would like to go to our <?php echo HTML::link('/', 'home page'); ?>?
			</p>
	    </div>
	</div>
</div>
@endsection