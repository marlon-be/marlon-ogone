<?php
namespace Ogone\ParameterFilter;

class GeneralParameterFilter implements ParameterFilter
{
	public function filter(array $parameters)
	{
		$parameters = array_change_key_case($parameters, CASE_UPPER);
		array_walk($parameters, 'trim');
		$parameters = array_filter($parameters, function($value){ return !is_null($value);});

		return $parameters;
	}
}