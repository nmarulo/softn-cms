<?php

/**
 * SilverEngine  - PHP MVC framework
 *
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

namespace App\Controllers;

use Silver\Core\Controller;
use Silver\Http\View;

class WelcomeController extends Controller
{
    private $model_name = false;
    private $table = false;

    public function welcome()
    {
        return View::demo();
    }

    public function demo()
    {
        $data = [];
        return View::make('welcome')->withComponent($data);
    }
}
