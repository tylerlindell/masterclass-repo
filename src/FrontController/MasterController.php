<?php

namespace Masterclass\FrontController;

use Aura\Di\Container;
use Aura\Web\Response;
use Masterclass\Router\Router;

class MasterController {

    /**
     * array containing configuration and routing details
     * @var Container
     */
    protected $container;

    /**
     * @var Router
     */
    protected $router;
    
    /**
     * @param array $config
     */
    public function __construct(Container $container, array $config = [], Router $router) {
        $this->config = $config;
        $this->container = $container;
        $this->router = $router;
    }
    
    /**
     * find and autoload object's and their methods based on url
     * @return mixed -what ever is returned by the method via the object called.
     */
    public function execute() {
        $match = $this->_determineControllers();

        $calling = $match->getRouteClass();
        list($class, $method) = explode(':', $calling);
        $o = $this->container->newInstance($class);
        $response = $o->$method();
        if($response instanceof Response){
            $this->sendResponse($response);
        }
    }

    public function sendResponse(Response $response)
    {
       header($response->status->get(), true, $response->status->getCode());

       //send non-cookie headers
       foreach ($response->headers->get() as $label => $value) {
           header("{$label}: {$value}");
       }

       //send cookies
       foreach ($response->cookies->get() as $name => $cookie) {
           setcookie(
                $name,
                $cookie['value'],
                $cookie['expire'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httponly']
            );
       }
       header('Connection: close');


       // send content
       print($response->content->get());

    }
    
    /**
     * get the controller that we are supposed to run
     * @return array
     */
    protected function _determineControllers()
    {
       $router = $this->router;
       $match = $router->findMatch();

       if(!$match){
            throw new \Exception('No route match found!');
            
       }
        
        return $match;
    }
    
}