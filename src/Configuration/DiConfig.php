<?php

namespace Masterclass\Configuration;

use Aura\Di\Config;
use Aura\Di\Container;

/**
* 
*/
class DiConfig extends Config
{
	
	public function define(Container $di)
	{
		$config = $di->get('config');

		$db = $config['database'];

		$dsn = 'mysql:host='. $db['host'] .';dbname='. $db['name'];

		$di->params['Masterclass\Dbal\AbstractDb'] = [
			'dsn'      => $dsn,
			'user' => $db['user'],
			'pass'   => $db['pass'],
		];

		$di->params['Masterclass\Model\Comment'] = [
			'db' => $di->lazyNew('Masterclass\Dbal\Mysql'),
		];

		$di->params['Masterclass\Model\Story'] = [
			'db' => $di->lazyNew('Masterclass\Dbal\Mysql'),
		];

		$di->params['Masterclass\Model\User'] = [
			'db' => $di->lazyNew('Masterclass\Dbal\Mysql'),
		];

		$di->params['Masterclass\FrontController\MasterController'] = [
			'container' => $di,
			'config' => $config,
			'router' => $di->lazyNew('Masterclass\Router\Router'),
		];

		$di->params['Masterclass\Controller\Comment'] = [
		 	'commentModel' => $di->lazyNew('Masterclass\Model\Comment'),
		 	'request' => $di->lazyNew('Aura\Web\Request'),
		 	'response' => $di->lazyNew('Aura\Web\Response'),
		];

		$di->params['Masterclass\Controller\Story'] = [
			'storyModel' => $di->lazyNew('Masterclass\Model\Story'),
			'commentModel' => $di->lazyNew('Masterclass\Model\Comment'),
			'request' => $di->lazyNew('Aura\Web\Request'),
			'response' => $di->lazyNew('Aura\Web\Response'),
		 	'view' => $di->lazyNew('Aura\View\View'),
		];

		$di->params['Masterclass\Controller\Index'] = [
			'storyModel' => $di->lazyNew('Masterclass\Model\Story'),
			'request' => $di->lazyNew('Aura\Web\Request'),
			'response' => $di->lazyNew('Aura\Web\Response'),
		 	'view' => $di->lazyNew('Aura\View\View'),
		];

		$di->params['Masterclass\Controller\User'] = [
			'userModel' => $di->lazyNew('Masterclass\Model\User'),
			'request' => $di->lazyNew('Aura\Web\Request'),
			'response' => $di->lazyNew('Aura\Web\Response'),
		 	'view' => $di->lazyNew('Aura\View\View'),
		];


	}
}