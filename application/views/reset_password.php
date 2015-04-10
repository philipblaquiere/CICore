<div class="container" >
	<div class="row">
		<?php echo $website_name?>
	</div>
	
	<div class="row login">
		<div class="col-sm-4 col-sm-offset-4">
			<?php echo form_open('', array('id' => 'reset-password-form')); ?>
			<div class="form-group">
				<?php echo validation_errors(); ?>
			</div>
			<div class="form-group">
				<span  class="help-block ">Enter your new passwords below. Make sure they match and are at least 6 characters long.</span>
				<?php echo form_password(array('name' => 'password', 'id'=>'password', 'class' => 'form-control', 'placeholder' => 'New password' )); ?>
			</div>
			<div class="form-group">
				<?php echo form_password(array('name' => 'password2', 'id'=>'password2', 'class' => 'form-control', 'placeholder' => 'Confirm new password' )); ?>
			</div>
			<div class="form-group pull-right">
				<?php echo form_submit('submit', 'Reset Password', "class='btn btn-default pull-left'"); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>