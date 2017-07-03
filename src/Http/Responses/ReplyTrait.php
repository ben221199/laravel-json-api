<?php

/**
 * Copyright 2017 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CloudCreativity\LaravelJsonApi\Http\Responses;

use CloudCreativity\JsonApi\Contracts\Store\StoreInterface;
use CloudCreativity\LaravelJsonApi\Api\Api;

/**
 * Class ReplyTrait
 *
 * @package CloudCreativity\LaravelJsonApi
 */
trait ReplyTrait
{

    /**
     * Get the API that is handling the inbound request.
     *
     * @return Api
     */
    public function api()
    {
        return app('json-api.inbound');
    }

    /**
     * Get the store for the current API.
     *
     * @return StoreInterface
     */
    public function store()
    {
        return $this->api()->getStore();
    }

    /**
     * @return Responses
     */
    public function reply()
    {
        return $this->api()->createResponse();
    }
}
