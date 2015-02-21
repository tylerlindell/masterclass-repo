<?php

namespace Masterclass\Router\Route;

/**
* 
*/
abstract class AbstractRoute implements RouterInterface
{
	protected $routePath;

	protected $routeClass;

	function __construct($routePath, $routeClass)
	{
		$this->routeClass = $routeClass;
		$this->routePath = $routePath;
	}

	public function getRoutePath()
	{
		return $this->routePath;
	}

	public function getRouteClass()
	{
		return $this->routeClass;
	}

	abstract public function matchRoute($requestPath, $requestType);
	
}