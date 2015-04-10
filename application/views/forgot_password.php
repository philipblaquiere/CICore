<div class="container" >
	<div class="row">
		<?php echo $website_name?>
	</div>
	<div class="row login">
		<div class="col-sm-4 col-sm-offset-4">
			<div id="reset-password-content">
				<?php echo form_open('', array('id' => 'forgotform')); ?>
				<div class="form-group">
					<span id="helpBlock" class="help-block ">If your email is registered with us, we'll send you an email with information required to reset your password.</span>
					<?php echo form_input(array('name' => 'email', 'id'=>'forgot_email', 'class' => 'form-control', 'placeholder' => 'Email' )); ?>
				</div>
				<div class="form-group pull-right">
					<?php echo form_submit('submit', 'Reset Password', "class='btn btn-default forgot_password_btn pull-left'"); ?>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>