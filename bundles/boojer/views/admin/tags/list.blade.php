@layout('admin::layouts.main')

@section('page_title')
| Tag - List
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('boojer::admin.sidenav')
	</div>
    <div class="span9">
    	<a href="<?=$controller_alias;?>/create_tag" class="btn pull-right btn-primary">Create Tag</a>
     	<h2>Current Tags</h2>
     	<hr>
     	<?php if(isset($tags) && !empty($tags)): ?>
     		<table class="table table-striped list-table">
     			<thead>
     				<tr>
     					<th>Name</th>
     					<th>Type</th>
     					<th class="align-right">Actions</th>
     				</tr>
     			</thead>
     			<tbody>
		     		<?php foreach($tags as $item): ?>
		     			<tr>
		     				<td><?=$item->name;?></td>
		     				<td><?=$item->type;?></td>
		     				<td>
								<div class="btn-toolbar">
									<div class="btn-group">
				     					<a href="<?=$controller_alias;?>/edit_tag/<?=$item->id;?>" class="btn btn-mini"><i class="icon-edit"></i> Edit</a>
				     					<a href="<?=$controller_alias;?>/destroy_tag/<?=$item->id;?>" class="btn btn-danger btn-mini" data-action="confirm"><i class="icon-remove icon-white"></i> Delete</a>
		     						</div>
		     					</div>
		     				</td>
		     			</tr>
		     		<?php endforeach; ?>
	     		</tbody>
	     	</table>
	    <?php else: ?>
	    	<p>No Tags.</p>
	    	<p><a  class="btn btn-primary" href="<?=$controller_alias;?>/create_tag">Create Tag</a></p>
     	<?php endif; ?>
    </div>
</div>
@endsection
