<?php

/**
 * SilverEngine  - PHP MVC framework
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

return [
        10 => Silver\App\Middlewares\ErrorHandler::class,
        20 => Silver\App\Middlewares\AccessLog::class,
        30 => Silver\App\Middlewares\Version::class,
        40 => App\Middlewares\ApiMiddleware::class,
        50 => App\Middlewares\LoginMiddleware::class,
        60 => App\Middlewares\AuthMiddleware::class,
];
