<form action="<?php echo $form->action?>" method="<?php echo $form->method?>" class="form-search form-horizontal">

	<?php echo View::factory('formmanager/html/fields', array('fields' => $form->fields))->render(); ?>

	<div class="form-actions">
		<input type="submit" class="btn btn-primary" value="<?php echo $form->submit_text; ?>" />
		<input type="reset" class="btn" value="Clear" />
    </div>

</form>
