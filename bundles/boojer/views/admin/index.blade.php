@layout('admin::layouts.main')

@section('page_title')
| Boojers - Dashboard
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
    	<h1>Manage Boojers</h1>
    	<div class="alert alert-block">
    		Use the navigation to the left to manage your content
    	</div>
    </div>
</div>
@endsection
