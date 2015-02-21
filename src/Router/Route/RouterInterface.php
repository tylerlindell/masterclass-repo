<?php

namespace Masterclass\Router\Route;

/**
* 
*/
interface RouterInterface
{
	
	public function matchRoute($requestType, $requestPath);

	public function getRoutePath();

	public function getRouteClass();
	
}