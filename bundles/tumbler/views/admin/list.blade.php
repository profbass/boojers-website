@layout('admin::layouts.main')

@section('page_title')
| Tumbler - List
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('tumbler::admin.sidenav')
	</div>
    <div class="span9">
    	<a href="<?=$controller_alias;?>/create" class="btn pull-right btn-primary">Create New Tumbler</a>
     	<h2>Current Tumbler Entries</h2>
     	<hr>
     	<?php if(!empty($items)): ?>
     		<table class="table table-striped list-table">
     			<thead>
     				<tr>
     					<th>Title</th>
     					<th>Visible</th>
     					<th>Type</th>
     					<th>Date Created</th>
     					<th class="align-right">Actions</th>
     				</tr>
     			</thead>
     			<tbody>
		     		<?php foreach($items as $item): ?>
		     			<tr>
		     				<td><?=$item->title;?></td>
		     				<td><? if ($item->visible == 1) echo 'Visible'; else echo 'Hidden'; ?></td>
		     				<td><?=$types[$item->type];?></td>
		     				<td><?=date('F j, Y', strtotime($item->created_at));?></td>
		     				<td>
								<div class="btn-toolbar">
									<div class="btn-group">
				     					<a href="<?=$controller_alias;?>/edit/<?=$item->id;?>" class="btn btn-mini"><i class="icon-edit"></i> Edit</a>
				     					<a href="<?=$controller_alias;?>/destroy/<?=$item->id;?>" class="btn btn-danger btn-mini" data-action="confirm"><i class="icon-remove icon-white"></i> Delete</a>
		     						</div>
		     					</div>
		     				</td>
		     			</tr>
		     		<?php endforeach; ?>
	     		</tbody>
	     	</table>
	    <?php else: ?>
	    	<p>No Entries.</p>
	    	<p><a href="<?=$controller_alias;?>/create" class="btn btn-primary">Create New Tumbler</a></p>
     	<?php endif; ?>
    </div>
</div>
@endsection
