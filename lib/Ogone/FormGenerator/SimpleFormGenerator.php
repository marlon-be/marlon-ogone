<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\FormGenerator;

use Ogone\Ecommerce\EcommercePaymentRequest;

class SimpleFormGenerator implements FormGenerator
{
    /**
     * @deprecated
     * @var null
     */
    private $formName = null;

    /**
     * @deprecated
     * @var null
     */
    private $showSubmitButton = null;

    /**
     * @param EcommercePaymentRequest $ecommercePaymentRequest
     * @param string $formName
     * @param bool $showSubmitButton
     * @param string $textSubmitButton The text displayed on the submit button of the form. Defaults to "Submit"
     * @return string HTML
     */
    public function render(EcommercePaymentRequest $ecommercePaymentRequest, $formName = 'ogone', $showSubmitButton = true, $textSubmitButton = 'Submit')
    {
        $formName = null !== $this->formName?$this->formName:$formName;
        $showSubmitButton = null !== $this->showSubmitButton?$this->showSubmitButton:$showSubmitButton;

        ob_start();
        include __DIR__.'/template/simpleForm.php';
        return ob_get_clean();
    }

    /**
     * @deprecated Will be removed in next major released, directly integrated in render method.
     * @param bool $bool
     */
    public function showSubmitButton($bool = true)
    {
        $this->showSubmitButton = $bool;
    }

    /**
     * @deprecated Will be removed in next major released, directly integrated in render method.
     * @param $formName
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }
}
