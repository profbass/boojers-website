<?php

class Boojer_Base_Controller extends Controller {
    public $restful = true;
    public $layout = 'layouts.main';
    public $action_urls = array();
    public $controller_alias = '';
    public $view_arguments = array();

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Catch-all method for requests that can't be matched.
     *
     * @param  string    $method
     * @param  array     $parameters
     * @return Response
     */
    public function __call($method, $parameters)
    {
        return Response::error('404');
    }
}