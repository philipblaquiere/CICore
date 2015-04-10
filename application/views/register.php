<div class="container">
  <!-- Header -->
  <div class="row">
    <div class="page-header">
      <h1>Register to <?php echo $website_name?></h1>
      <p><h3><small>Registration is a necessary thing, ok?</small></h3></p>
    </div>
  </div>
  <!-- Header -->
            
  <!-- Register Content -->
  <div class="row">
    <?php echo validation_errors(); ?>
    <?php echo form_open('register', array('class' => 'form-horizontal', 'id' => 'initial-registration')); ?>
      <div class="form-group">
        <?php echo form_label('Email', 'name', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10">
          <input name="email" class="form-control" placeholder="Email" id="email" value="<?php echo set_value('email'); ?>" />
        </div>
      </div>
      <div class="form-group">
        <?php echo form_label('Password', 'name', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10">
          <input type="password" name="password" class="form-control" placeholder="Password" id="password" />
        </div>
      </div>
      <div class="form-group">
        <?php echo form_label('Password Confirmation', 'name', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10">
          <input type="password" name="passconf" class="form-control" placeholder="^" id="passconf" />
        </div>
      </div>
      <div class="form-group">
        <div class="page-header"/>
      </div>
      <div class="pull-right">
        <?php echo form_submit('submit', 'Register', "class='btn btn-default' id='register_submit'"); ?>
      </div>
    <?php echo form_close(); ?>
  </div>
  <!-- End Register Content -->
</div>