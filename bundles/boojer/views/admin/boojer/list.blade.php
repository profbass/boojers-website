@layout('admin::layouts.main')

@section('page_title')
| Boojer - List
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
    	<a href="<?=$controller_alias;?>/create" class="btn pull-right btn-primary">Create Boojer</a>
     	<h2>Current Boojers</h2>
     	<hr>
     	<?php if(isset($boojers) && !empty($boojers)): ?>
     		<table class="table table-striped list-table">
     			<thead>
     				<tr>
     					<th>Name</th>
     					<th>Email</th>
     					<th class="align-right">Actions</th>
     				</tr>
     			</thead>
     			<tbody>
		     		<?php foreach($boojers as $item): ?>
		     			<tr>
		     				<td><?=$item->first_name;?> <?=$item->last_name;?></td>
		     				<td><?=$item->email;?></td>
		     				<td>
								<div class="btn-toolbar">
									<div class="btn-group">
				     					<a href="<?=$controller_alias;?>/edit/<?=$item->id;?>" class="btn btn-mini"><i class="icon-edit"></i> Edit Info</a>
				     					<a href="<?=$controller_alias;?>/destroy/<?=$item->id;?>" class="btn btn-danger btn-mini" data-action="confirm"><i class="icon-remove icon-white"></i> Delete</a>
		     						</div>
		     					</div>
		     				</td>
		     			</tr>
		     		<?php endforeach; ?>
	     		</tbody>
	     	</table>
	    <?php else: ?>
	    	<p>No Boojers. <a  class="btn btn-primary" href="<?=$controller_alias;?>/create">Create Boojer</a></p>
     	<?php endif; ?>

    </div>
</div>
@endsection
