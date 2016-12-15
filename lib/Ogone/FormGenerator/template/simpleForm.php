<form method="post" action="<?php echo $ecommercePaymentRequest->getOgoneUri()?>" id="<?php echo $formName?>" name="<?php echo $formName?>">
<?php foreach ($ecommercePaymentRequest->toArray() as $key => $value) : ?>
    <?php if (false !== $value) : ?>
    <input type="hidden" name="<?php echo $key?>" value="<?php echo htmlspecialchars($value) ?>"  />
    <?php endif ?>
<?php endforeach ?>
<input type="hidden" name="<?php echo Ogone\PaymentRequest::SHASIGN_FIELD ?>" value="<?php echo $ecommercePaymentRequest->getShaSign()?>" />

<?php if ($showSubmitButton) : ?>
    <input name="ogonesubmit" type="submit" value="<?php echo $textSubmitButton ?>" id="ogonesubmit" />
<?php endif ?>
</form>
