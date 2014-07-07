<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\DirectLink;

use Ogone\AbstractPaymentResponse;
use SimpleXMLElement;
use InvalidArgumentException;

class DirectLinkQueryResponse extends AbstractPaymentResponse {

    public function __construct($xml_string)
    {
        libxml_use_internal_errors(true);

        if (simplexml_load_string($xml_string)) {

            $xmlResponse = new SimpleXMLElement($xml_string);

            $attributesArray = $this->xmlAttributesToArray($xmlResponse->attributes());

            // use lowercase internally
            $attributesArray = array_change_key_case($attributesArray, CASE_UPPER);

            // filter request for Ogone parameters
            $this->parameters = $this->filterRequestParameters($attributesArray);

        } else {

            throw new InvalidArgumentException("No valid XML-string given");
        }
    }

    public function isSuccessful()
    {
        return (0 == $this->getParam('NCERROR'));
    }

    protected function filterRequestParameters(array $httpRequest)
    {
        return array_intersect_key(
            $httpRequest,
            array_flip(
                array_merge(
                    $this->ogoneFields,
                    array(
                        'PAYIDSUB',
                        'NCSTATUS',
                        'NCERRORPLUS',
                    )
                )
            )
        );
    }

    private function xmlAttributesToArray($attributes)
    {
        $attributesArray = array();

        if (count($attributes)) {
            foreach ($attributes as $key => $value) {
                $attributesArray[(string)$key] = (string)$value;
            }
        }

        return $attributesArray;
    }
}
