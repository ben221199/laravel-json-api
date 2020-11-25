<?php

/**
 * Copyright 2019 Cloud Creativity Limited
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

namespace CloudCreativity\LaravelJsonApi\Eloquent;

use CloudCreativity\LaravelJsonApi\Routing\ResourceRegistrar;
use Illuminate\Support\Facades\Route;
use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * Class AbstractSchema
 *
 * @package CloudCreativity\LaravelJsonApi
 */
abstract class AbstractSchema extends SchemaProvider
{

	public function __construct(SchemaFactoryInterface $factory)
	{
		$this->resolveSubUrl();
		parent::__construct($factory);
	}

	public function resolveSubUrl()
	{
		//Check for custom route
		$route = Route::getCurrentRoute();
		if ($route !== null) {
			$uri = $route->parameter(ResourceRegistrar::PARAM_RESOURCE_URI);
			if($uri !== null) {
				$this->selfSubUrl =  (substr($uri, 0) === '/') ? $uri : '/' . $uri;
				return;
			}
		}
		//Check for $selfSubUrl
		if($this->selfSubUrl !== null){
			$this->selfSubUrl = $this->getSelfSubUrl();
			return;
		}
		//Fallback on resource type
		$this->selfSubUrl = '/' . $this->getResourceType();
	}

}