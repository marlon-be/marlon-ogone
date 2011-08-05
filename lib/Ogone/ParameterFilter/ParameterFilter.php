<?php
namespace Ogone\ParameterFilter;

interface ParameterFilter
{
	/** @return array Filtered parameters */
	function filter(array $parameters);
}