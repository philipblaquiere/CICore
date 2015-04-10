<div class="container" >
	<div class="row">
		<?php echo $website_name?>
	</div>
	<div class="row login ">
		<div class="col-sm-4 col-sm-offset-4">
			  <?php echo validation_errors(); ?>
			<?php echo form_open('auth/sign_in', array('id' => 'signinform')); ?>
			<div class="form-group">
				<?php echo form_input(array('name' => 'email', 'class' => 'form-control', 'placeholder' => 'Email' )); ?>
			</div>
			<div class="form-group">
				<?php echo form_password(array('name' => 'password', 'class' => 'form-control', 'placeholder' => 'Password')); ?>
			</div>
			<a href="<?php echo site_url('auth/forgot'); ?>">Forgot Password?</a>
			<div class="form-group pull-right">
				<?php echo form_submit('submit', 'Sign In', "class='btn btn-default btn-sm pull-left'"); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>