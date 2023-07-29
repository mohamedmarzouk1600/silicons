<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/12/2018 2:03 AM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use MaxDev\Modules\WebBaseController;
use MaxDev\Services\AdminTemplate\AdminTemplateService;

class AdministratorsController extends WebBaseController
{
    public $routePrefix = '';
    public $template;
    public function __construct()
    {
        $this->viewData['routePrefix'] = $this->routePrefix;
        $this->middleware('auth:admin')->except(['login','ShowLoginForm']);
        $this->template = new AdminTemplateService();
    }
}
