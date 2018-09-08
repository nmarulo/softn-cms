<?php

namespace App\Facades\Api;

use Psr\Http\Message\ResponseInterface;
use Silver\Database\Model;
use Silver\Support\Facade;

/**
 * @method static get(string $uri, array | Model | int $data)
 * @method static head(string $uri, array | Model $data)
 * @method static put(string $uri, array | Model $data)
 * @method static post(string $uri, array | Model $data)
 * @method static patch(string $uri, array | Model $data)
 * @method static delete(string $uri, array | Model $data)
 * @method static getAsync(string $uri, array | Model | int $data)
 * @method static headAsync(string $uri, array | Model $data)
 * @method static putAsync(string $uri, array | Model $data)
 * @method static postAsync(string $uri, array | Model $data)
 * @method static patchAsync(string $uri, array | Model $data)
 * @method static deleteAsync(string $uri, array | Model $data)
 * @method static bool isError()
 * @method static string getMessage()
 * @method static string getBody()
 * @method static ResponseInterface getResponse()
 * @method static int getStatusCode()
 * @method static string getToken()
 * @method static array responseJsonDecode(bool $assoc = TRUE)
 * @method static bool isGetRequest()
 * @method static bool isPostRequest()
 */
class RequestApiFacade extends Facade {
    
    protected static function getClass() {
        return 'App\Helpers\Api\RequestApiHelper';
    }
    
}
