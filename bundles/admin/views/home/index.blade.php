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
 			<li><a href="/admin/content">Content</a></li>
 			<li><a href="/admin/users">Users</a></li>
 			<li><a href="/admin/myaccount">My Account</a></li>
 			<li>&nbsp;</li>
 			<li><a href="/admin/logout">Logout</a></li>
 		</ul>
	</div>
</div>
@endsection