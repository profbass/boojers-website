@layout('admin::layouts.main')

@section('page_title')
| Dashboard
@endsection

@section('content')
<div class="row-fluid">
    <div class="span12">
 		<h1>Admin Dashboard</h1>
 		
 		<p>Welcome to your admin panel. Use the navigation at the top to manage your site.</p>

 		<ul class="unstyled">
 			<? if (!empty($menu)): ?>
 				<? foreach ($menu as $key => $value): ?>
 					<li><a href="<?=$value;?>"><?=$key;?></a></li>
 				<? endforeach; ?>
 			<? endif; ?>
 		</ul>
	</div>
</div>
@endsection