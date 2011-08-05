<form method="post" action="<?php echo $this->getOgoneUri()?>" id="ogone" name="ogone">

	<?php foreach($this->getParameters() as $key => $value) :?>
		<?php if($value) :?>
			<input type="hidden" name="<?php echo $key?>" value="<?php echo htmlspecialchars($value) ?>">
		<?php endif?>
	<?php endforeach?>
	<input type="hidden" name="SHASIGN" value="<?php echo $this->getShaSign()?>" />
	<input type="submit" value="Submit" id="submit" name="submit">

</form>