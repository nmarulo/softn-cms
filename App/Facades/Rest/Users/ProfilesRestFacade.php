<?php
/**
 * softn-cms
 */

namespace App\Facades\Rest\Users;

use App\Rest\Calls\Users\ProfilesRest;
use App\Rest\Requests\Users\ProfileRequest;
use App\Rest\Responses\Users\ProfileResponse;
use App\Rest\Responses\Users\ProfilesResponse;
use Silver\Support\Facade;

/**
 * @method static ProfilesResponse getAll(ProfileRequest $request = NULL)
 * @method static ProfileResponse getById(int $id)
 * @method static ProfileResponse create(ProfileRequest $request)
 * @method static ProfileResponse update(int $id, ProfileRequest $request)
 * @method static ProfileResponse updatePassword(int $id, ProfileRequest $request)
 * @method static bool remove(int $id)
 * @method static bool isError()
 * Class ProfilesRestFacade
 * @author Nicolás Marulanda P.
 */
class ProfilesRestFacade extends Facade {
    
    protected static function getClass() {
        return ProfilesRest::class;
    }
}
