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

namespace CloudCreativity\LaravelJsonApi\Routing;

use Closure;
use CloudCreativity\LaravelJsonApi\Api\Repository;
use CloudCreativity\LaravelJsonApi\Api\ResourceProviders;
use Illuminate\Contracts\Routing\Registrar;

/**
 * Class ResourceRegistrar
 *
 * @package CloudCreativity\LaravelJsonApi
 */
class ResourceRegistrar
{

    const KEYWORD_RELATIONSHIPS = 'relationships';
    const PARAM_RESOURCE_TYPE = 'resource_type';
    const PARAM_RESOURCE_ID = 'resource_id';
    const PARAM_RELATIONSHIP_NAME = 'relationship_name';

    /**
     * @var Registrar
     */
    protected $router;

    /**
     * @var Repository
     */
    protected $apiRepository;

    /**
     * ResourceRegistrar constructor.
     *
     * @param Registrar $router
     * @param Repository $apiRepository
     */
    public function __construct(Registrar $router, Repository $apiRepository)
    {
        $this->router = $router;
        $this->apiRepository = $apiRepository;
    }

    /**
     * @param $apiName
     * @param array $options
     * @param Closure $routes
     * @return void
     */
    public function api($apiName, array $options, Closure $routes)
    {
        $options = $this->pushMiddleware($apiName, $options);
        $api = $this->apiGroup($apiName, $options);
        $providers = $this->apiProviders($apiName);

        $this->router->group($options, function () use ($api, $routes, $providers) {
            $routes($api, $this->router);
            $providers->mountAll($api, $this->router);
        });
    }

    /**
     * @param $apiName
     * @param array $options
     * @return array
     */
    protected function pushMiddleware($apiName, array $options)
    {
        $middleware = (array) array_get($options, 'middleware');
        array_unshift($middleware, "json-api:$apiName");

        $options['middleware'] = $middleware;

        return $options;
    }

    /**
     * @param $apiName
     * @param array $options
     * @return ApiGroup
     */
    protected function apiGroup($apiName, array $options)
    {
        $definition = $this->apiRepository->createApi($apiName);

        return new ApiGroup($this->router, $definition, $options);
    }

    /**
     * @param $apiName
     * @return ResourceProviders
     */
    protected function apiProviders($apiName)
    {
        return $this->apiRepository->createProviders($apiName);
    }
}
