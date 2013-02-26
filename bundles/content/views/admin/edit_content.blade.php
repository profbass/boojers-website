@layout('admin::layouts.main')

@section('page_title')
| Content - Edit Content
@endsection

@section('scripts')
<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script>
if (typeof CKEDITOR === 'object') {
    var editor = CKEDITOR.replace( 'editor1' );
    CKFinder.setupCKEditor( editor, '/ckfinder/' );
}
</script>
@endsection

@section('content')
<div class="row-fluid">
	<div class="span3">
		@include('content::admin.sidenav')
	</div>
    <div class="span9">
    	<h2>Editing Content For "<?=$page->pretty_name; ?>"</h2>
		<ul class="nav nav-tabs">
			<li><a href="<?=$controller_alias;?>/edit/<?=$page->id;?>">Edit Page Information</a></li>
			<li class="active"><a>Edit Content</a></li>
		</ul>

		<?=Form::open($controller_alias . '/update_content/' . $page->id . '/' . $page->cmspage->id, null, array('class' => 'form-horizontal') ); ?>
			<fieldset>
				<?=Form::hidden('id', $page->cmspage->id); ?>
				<div class="control-group">
					<?=Form::label('content', 'Content', array('class' => 'control-label')); ?>
					<div class="controls">
						<?=Form::textarea('content', Input::old('content') ? Input::old('content') : $page->cmspage->content, array('cols' => '80', 'rows' => '10', 'class' => 'span12', 'id' => 'editor1', 'style' => 'height: 400px;', 'placeholder' => 'Enter Content'));?>
						@if ($errors && $errors->has('content'))
							<span class="help-block">This field is required</span>
						@endif
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<div class="alert alert-block">For a reference on what markup to use and all the style resources visit <a href="http://www.getbootstrap.com" target="_blank">www.getbootstrap.com</a> and navigate to the <strong>Base CSS page</strong></div>
					</div>
				</div>

				<div class="control-group">
					<?=Form::label('scripts', 'Scripts', array('class' => 'control-label')); ?>
					<div class="controls">
						<?=Form::textarea('scripts', Input::old('scripts') ? Input::old('scripts') : $page->cmspage->scripts, array('class' => 'span12', 'style' => 'height: 200px;', 'placeholder' => 'Enter Javascript')); ?>
						<span class="help-block">You may enter any javascript or javascript includes in this section. Make sure you use <strong>&lt;script&gt;...&lt;/script&gt;</strong> tags</span>
					</div>
				</div>

				<div class="control-group">
					<?=Form::label('styles', 'Styles', array('class' => 'control-label')); ?>
					<div class="controls">
						<?=Form::textarea('styles', Input::old('styles') ? Input::old('styles') : $page->cmspage->styles, array('class' => 'span12', 'style' => 'height: 200px;', 'placeholder' => 'Enter Any CSS'));?>
						<span class="help-block">You may enter any css or css includes in this section. Make sure you use <strong>&lt;style&gt;...&lt;/style&gt;</strong> tags</span>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-large btn-success">Save Changes</button>
				</div>
				
				<?=Form::token(); ?>
			</fieldset>
   		<?=Form::close(); ?>	


    </div>
</div>
@endsection