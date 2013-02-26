<?php
class Admin_Base_Controller extends Controller {
    public $restful = true;
    public $layout = 'admin::layouts.main';
    public $admin_alias = '/admin';
    public $controller_alias = '';
    public $view_arguments = array();

    public function __construct()
    {
        parent::__construct();

        Config::set('auth.driver', 'adminauth');

        $this->filter('before', 'admin_auth');

        $this->admin_alias = Config::get('Admin::admin.admin_alias');

        $this->controller_alias = $this->admin_alias;

        $this->view_arguments['controller_alias'] = $this->controller_alias;
        $this->view_arguments['admin_alias'] = $this->admin_alias;
        
        $this->view_arguments['main_admin_nav'] = Config::get('Admin::admin.main_nav');
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