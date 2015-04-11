  <!-- Static navbar -->

  <nav class="navbar navbar-fixed-top" role="navigation">
    <div class="container container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand site-header brand" href="<?php echo site_url('home'); ?>"><?php echo $website_name?></a>
      </div>

      <div id="main-navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <?php if ($is_logged_in): ?>
            <li class="dropdown">
              <a class="btn btn-link" href="<?php echo site_url('home')?>">Profile</a>
            </li>
            <li>
              <a href="<?php echo site_url('auth/sign_out'); ?>">Sign out</a>
            </li>
          <?php else: ?>
            <li><a href="<?php echo site_url('register'); ?>">Register</a></li>
            <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In<strong class="caret"></strong></a>
              <div class="dropdown-menu sign_in_mini">
                <div class="row login ">
                  <div class="col-sm-12">
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
            <?php endif; ?>           
          </ul>
        </div>
      </div>
  </nav>
  <!-- Static nav -->
  <div class="wrapper">
  <!-- Wrapper -->