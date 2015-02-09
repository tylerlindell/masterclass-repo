<?php

namespace Masterclass\FrontController;

class MasterController {
    
    /**
     * array containing configuration and routing details
     * @var array
     */
    private $config;
    
    /**
     * @param array $config
     */
    public function __construct($config) {
        $this->_setupConfig($config);
    }
    
    /**
     * find and autoload object's and their methods based on url
     * @return mixed -what ever is returned by the method via the object called.
     */
    public function execute() {
        $call = $this->_determineControllers();
        $call_class = $call['call'];
        $class = ucfirst(array_shift($call_class));
        $method = array_shift($call_class);
        $o = new $class($this->config);
        return $o->$method();
    }
    
    /**
     * get the controller that we are supposed to run
     * @return array
     */
    private function _determineControllers()
    {
        if (isset($_SERVER['REDIRECT_BASE'])) {
            $rb = $_SERVER['REDIRECT_BASE'];
        } else {
            $rb = '';
        }
        
        $ruri = $_SERVER['REQUEST_URI'];
        $path = str_replace($rb, '', $ruri);
        $return = array();
        
        foreach($this->config['routes'] as $k => $v) {
            $matches = array();
            $pattern = '$' . $k . '$';
            if(preg_match($pattern, $path, $matches))
            {
                $controller_details = $v;
                $path_string = array_shift($matches);
                $arguments = $matches;
                $controller_method = explode(':', $controller_details);
                $return = array('call' => $controller_method);
            }
        }
        
        return $return;
    }
    
    /**
     * assign variable
     * @param  array $config
     * @return void
     */
    private function _setupConfig($config) {
        $this->config = $config;
    }
    
}