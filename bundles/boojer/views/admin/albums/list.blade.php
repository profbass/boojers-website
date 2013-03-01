@layout('admin::layouts.main')

@section('page_title')
| Album - List
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
    	<a href="<?=$controller_alias;?>/create_album" class="btn pull-right btn-primary">Create Album</a>
     	<h2>Current Albums</h2>
     	<hr>
     	<?php if(!empty($albums)): ?>
     		<table class="table table-striped list-table">
     			<thead>
     				<tr>
     					<th>Name</th>
     					<th>Visible</th>
     					<th>Date Created</th>
     					<th class="align-right">Actions</th>
     				</tr>
     			</thead>
     			<tbody>
		     		<?php foreach($albums as $item): ?>
		     			<tr>
		     				<td><?=$item->name;?></td>
		     				<td><? if ($item->visible == 1) echo 'Visible'; else echo 'Hidden'; ?></td>
		     				<td><?=date('F j, Y', strtotime($item->created_at));?></td>
		     				<td>
								<div class="btn-toolbar">
									<div class="btn-group">
				     					<a href="<?=$controller_alias;?>/edit_album_photos/<?=$item->id;?>" class="btn btn-mini"><i class="icon-picture"></i> Manage Photos</a>
				     					<a href="<?=$controller_alias;?>/edit_album/<?=$item->id;?>" class="btn btn-mini"><i class="icon-edit"></i> Edit</a>
				     					<a href="<?=$controller_alias;?>/destroy_album/<?=$item->id;?>" class="btn btn-danger btn-mini" data-action="confirm"><i class="icon-remove icon-white"></i> Delete</a>
		     						</div>
		     					</div>
		     				</td>
		     			</tr>
		     		<?php endforeach; ?>
	     		</tbody>
	     	</table>
	    <?php else: ?>
	    	<p>No Albums.</p>
	    	<p><a  class="btn btn-primary" href="<?=$controller_alias;?>/create_album">Create Album</a></p>
     	<?php endif; ?>
    </div>
</div>
@endsection
