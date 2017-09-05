<?php

namespace App\Controller;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Phalcon\Mvc\Model\EagerLoading\Loader;
use Phalcon\Mvc\Model\EagerLoadingTrait;
use Phalcon\Mvc\Model\Resultset;
use PhalconUtils\Controller\BaseController;
use PhalconUtils\Transformer\Transformable;

/**
 * Class FractalController
 * @property Manager fractal
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Controllerh
 */
abstract class FractalController extends BaseController
{
    protected function createArrayResponse($array, $key)
    {
        $response = [$key => $array];

        return $this->createResponse($response);
    }

    protected function createResponse($response)
    {
        return $this->response->sendSuccess($response);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param Transformable|EagerLoadingTrait $item
     * @param $transformer
     * @param null $resourceKey
     * @param null $meta
     * @return mixed
     */
    protected function createItemResponse($item, $transformer, $resourceKey = null, $meta = null)
    {
        if ($item instanceof Transformable) {
            $modelsToLoad = $item->getModelsToLoad();
            if ($modelsToLoad) {
                $item = $item->load($item->getModelsToLoad());
            }
        }

        $resource = new Item($item, $transformer, $resourceKey);
        $data = $this->fractal->createData($resource)->toArray();
        $response = array_merge($data, $meta ? $meta : []);

        return $this->createResponse($response);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param Resultset $collection
     * @param $transformer
     * @param $modelsToLoad Transformable | array
     * @param null $resourceKey
     * @param null $meta
     * @return mixed
     */
    protected function createCollectionResponse(
        $collection,
        $transformer,
        $modelsToLoad,
        $resourceKey = null,
        $meta = null
    ) {
        if ($modelsToLoad instanceof Transformable) {
            $modelsToLoad = $modelsToLoad->getModelsToLoad();
        }

        if (!is_array($collection)) {
            $collection = Loader::fromResultset($collection, $modelsToLoad);
        }
        if (is_null($collection)) {
            $data = [];
        } else {
            $resource = new Collection($collection, $transformer, $resourceKey);
            $data = $this->fractal->createData($resource)->toArray();
        }
        $response = array_merge($data, $meta ? $meta : []);
        return $this->createResponse($response);
    }
}
