@layout('admin::layouts.main')

@section('page_title')
| Users - My Account
@endsection

@section('content')	
<div class="row-fluid">
    <div class="span3">
        @include('admin::my_account.sidenav')
    </div>
    <div class="span9">
    	<h2>My Account</h2>
    	<hr>
    	<table class="table table-striped">
    		<tbody>
                <tr>
                    <td>First Name</td>
                    <td><?=$user->first_name; ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?=$user->last_name; ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><?=$user->username; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?=$user->email;?></td>
                </tr> 

                <? if (!empty($user->groups)): ?>
                <tr>
                    <td>Groups</td>
                    <td>
                        <?
                        foreach($user->groups as $group) {
                            echo $group->name . "<br>";
                        }
                        ?>
                    </td>
                </tr>
                <? endif; ?>

                <?
                if (!empty($user->user_metadata->attributes)):
                    foreach($user->user_metadata->attributes as $meta_name => $meta_value): 
                ?>
                        <? if(!in_array($meta_name, array('id', 'user_id', 'avatar', 'avatar_small'))): ?>
                        <tr>
                            <td><?=ucwords(str_replace('_', ' ', $meta_name));?></td>
                            <td><?=$meta_value;?></td>
                        </tr>
                        <? elseif(in_array($meta_name, array('avatar'))): ?>
                            <tr>
                                <td>Avatar</td>
                                <td>
                                    <? if (!empty($meta_value)): ?>
                                        <a href="<?=$meta_value;?>" target="_blank"><img src="<?=$meta_value;?>" class="img-polaroid" alt="Avatar" style="max-width:100px;"></a>
                                    <? else: ?>
                                        No Image Uploaded
                                    <? endif; ?>
                                   <a href="<?=$controller_alias;?>/edit_avatar">Change/Upload Avatar</a>
                                </td>
                            </tr>   
                <?
                        endif;
                    endforeach;
                endif;
                ?>
    		</tbody>
    	</table>
    	<a href="<?=$controller_alias;?>/edit" class="btn btn-primary">Edit This Info</a>

    	<hr>
    	<h3>Change Password</h3>

		<?php echo Form::open($controller_alias . '/change_password', null, array('class' => 'form-horizontal') ); ?>
			<fieldset>

				<div class="control-group{{ isset($errors) && $errors->has('password') ? ' error' : '' }}">
					<div class="control-group">
						<?php echo Form::label('password', 'Password *', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php
								echo Form::password('password', array('class' => 'span6', 'required' => 'required', 'placeholder' => 'Enter New Password'));
							?>
							@if ($errors && $errors->has('password'))
								<span class="help-inline">This field is required</span>
							@endif
						</div>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" name="submit" value="1" class="btn btn-success">Change Password</button>
				</div>
				<?php echo Form::token(); ?>
			</fieldset>
   		<?php echo Form::close(); ?>	
</div>
@endsection