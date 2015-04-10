<div class="system-message">
	<div class="container ">
	<?php if ($system_messages): ?>
	    <?php foreach($system_messages as $type => $messages): ?>
	        <?php foreach($messages as $message): ?>
	        <div class="alert custom-alert alert-dismissable">
	          		<?php echo $message; ?>
	          		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	        </div>
	        <?php endforeach; ?>
	    <?php endforeach; ?>
	<?php endif; ?>
	</div>
</div>
