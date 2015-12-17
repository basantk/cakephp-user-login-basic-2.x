<html>
<head>
	<title>Cake Login</title>
	
</head>
<body>
	<h3>Log In</h3>
	<?php echo $this->Form->create('User'); ?>
	<?php echo $this->Form->input('username',array('div'=>false,'label'=>false,'hiddenField'=>false,'class'=>'form-control')); ?>
	<?php echo $this->Form->input('password',array('div'=>false,'label'=>false,'hiddenField'=>false,'class'=>'form-control')); ?>
	<?php echo $this->Form->submit('Submit',array('type'=>'submit'));?>
	<?php echo $this->Form->end(); ?>
</body>
</html>