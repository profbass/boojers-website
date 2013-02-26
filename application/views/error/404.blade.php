@layout('layouts.main')

@section('page_title')
| Error 400 - Page Not Found
@endsection


@section('content')
<div class="container">
	<div class="row-fluid">
	    <div class="span12">
			<?php if (isset($messages)) echo '<h1>' . $messages[mt_rand(0, 2)] . '</h1>'; ?>

			<h2>404 (Not Found)</h2>

			<hr>

			<p>
				We couldn't find the page you requested on our servers. We're really sorry
				about that. It's our fault, not yours. We'll work hard to get this page
				back online as soon as possible.
			</p>

			<p>
				Perhaps you would like to go to our <?php echo HTML::link('/', 'home page'); ?>?
			</p>
		</div>
	</div>
</div>
@endsection