@layout('layouts.main')

@section('page_title')
| Error 500 - Internal Server Error
@endsection


@section('content')
<div class="main-container">
	<div class="container-fluid">
		<div class="row-fluid">
		    <div class="span12">
				<?php if (isset($messages)) echo '<h1>' . $messages[mt_rand(0, 2)] . '</h1>'; ?>

				<h2>Server Error: 500 (Internal Server Error)</h2>

				<hr>

				<p>
					Well this is a bummer. Something broke pretty bad.			
				</p>

				<p>
					Perhaps you would like to go to our <?php echo HTML::link('/', 'home page'); ?>?
				</p>
			</div>
		</div>
	</div>
</div>
@include('layouts.footer')
@endsection