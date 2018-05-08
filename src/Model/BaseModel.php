<?php

namespace PhalconUtils\Model;

use League\Fractal\Pagination\PagerfantaPaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Pagerfanta\Pagerfanta;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\EagerLoading\Loader;
use Phalcon\Mvc\Model\EagerLoadingTrait;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\RelationInterface;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use PhalconUtils\Constants\Services;
use PhalconUtils\Pagination\PaginationAdapter;
use PhalconUtils\Util\DateUtils;

/**
 * Class BaseModel
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BaseModel extends Model
{
    use EagerLoadingTrait;

    private static $last_error_message = null;

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return mixed
     */
    public static function add($data)
    {
        $called_class = get_called_class();
        /** @var Model $model */
        $model = new $called_class();
        self::fill($data, $model);
        return ($model->save()) ? $model : false;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @param $model
     * @param string $prefix
     */
    public static function fill($data, $model, $prefix = '')
    {
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $prefix .= $key . "_";
                self::fill($value, $model, $prefix);
                $prefix = '';
            } else {
                $model->{$prefix . $key} = $value;
            }
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function notSaved()
    {
        self::setLastErrorMessage($this);

        if ($errorMessages = $this->getMessages()) {
            foreach ($errorMessages as $errorMessage) {
                self::getLogger()
                    ->error(get_called_class() . " " . $this->getSource() . " " . $errorMessage);
            }
        }
    }

    /**
     * Set created_at before validation
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function beforeValidationOnCreate()
    {
        $this->created_at = DateUtils::getCurrentDateTime();
        $this->updated_at = DateUtils::getCurrentDateTime();
    }

    /**
     * Set created_at before validation
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function beforeValidationOnUpdate()
    {
        $this->updated_at = DateUtils::getCurrentDateTime();
    }

    /**
     * Get application logger
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return \PhalconUtils\Util\Logger
     */
    public static function getLogger()
    {
        return Di::getDefault()->getLogger();
    }

    /**
     * Get application Config
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return Config
     */
    public static function getConfig()
    {
        return Di::getDefault()->getConfig();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public static function getLastErrorMessage()
    {
        return self::$last_error_message;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $model mixed
     */
    public static function setLastErrorMessage($model)
    {
        if ($model instanceof Model) {
            $messages = [];
            foreach ($model->getMessages() as $message) {
                $messages[] = $message;
            }

            self::$last_error_message = implode(',', $messages);
        } elseif (is_string($model)) {
            self::$last_error_message = $model;
        }
    }

    /**
     * Begin transaction
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public static function beginTransaction()
    {
        Di::getDefault()->get(Services::DB)->begin();
    }

    /**
     * Commit transaction
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public static function commitTransaction()
    {
        Di::getDefault()->get(Services::DB)->commit();
    }

    /**
     * Roll back transaction
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public static function rollBackTransaction()
    {
        Di::getDefault()->get(Services::DB)->rollback();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @param $columns
     * @param array $commonData
     * @return bool|int
     * @throws \Exception
     */
    public static function batchInsert($data, $columns, $commonData = [])
    {
        if (empty($data)) {
            return true;
        }

        $insertData = [];
        foreach ($data as $row) {
            $rowData = (array)$row;
            $rowInsertData = [];
            foreach ($columns as $key) {
                if (in_array($key, array_keys($commonData))) {
                    $rowInsertData[$key] = $commonData[$key];
                } else {
                    $rowInsertData[$key] = $rowData[$key];
                }
            }
            $insertData[] = $rowInsertData;
        }

        $model = get_called_class();
        $batch = new Batch((new $model())->getSource());
        $batch->setRows($columns);
        $batch->setValues($insertData);
        return $batch->insert();
    }

    /**
     * Get global events manger
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return EventsManager
     */
    public function getGlobalEventsManager()
    {
        return Di::getDefault()->get(Services::EVENTS_MANAGER);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $builder Model\Query\Builder
     * @param null $relatedRecordsToLoad
     * @param TransformerAbstract $transformer
     * @param null $shouldPaginate
     * @param null $resourceKey
     * @param null $meta
     * @return array
     */
    public static function getPaginatedData(
        $builder,
        $relatedRecordsToLoad = null,
        TransformerAbstract $transformer = null,
        $shouldPaginate = null,
        $resourceKey = null
    )
    {
        $shouldPaginate = is_null($shouldPaginate) ? self::shouldPaginate() : $shouldPaginate;
        $paginator = null;

        if ($shouldPaginate) {
            $page = self::getPage();
            $limit = self::getLimit();

            $paginatorBuilder = new PaginatorQueryBuilder([
                'builder' => $builder,
                'page' => $page,
                'limit' => $limit
            ]);
            $paginateObject = $paginatorBuilder->getPaginate();
            $pagerFanta = new Pagerfanta(new PaginationAdapter($paginatorBuilder, $paginateObject));
            $pagerFanta->setMaxPerPage($limit);
            $pagerFanta->setCurrentPage($paginateObject->current);
            $paginator = new PagerfantaPaginatorAdapter($pagerFanta, function () {
            });
            $items = $paginateObject->items;
        } else {
            $items = $builder->getQuery()->execute();
        }

        $collection = self::getCollection(
            $items,
            $relatedRecordsToLoad,
            $transformer
        );

        if ($shouldPaginate) {
            $collection->setPaginator($paginator);
            if (is_null($resourceKey)) {
                $collection->setResourceKey('items');
            }
        }

        return Di::getDefault()->get(Services::FRACTAL_MANAGER)->createData($collection)->toArray();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param null $data
     * @param null $relationsToLoad
     * @param TransformerAbstract $transformer
     * @return Collection
     */
    public static function getCollection($data = null, $relationsToLoad = null, TransformerAbstract $transformer = null)
    {
        /** @var Model $model */
        $modelName = get_called_class();
        $model = new $modelName();

        if (is_null($relationsToLoad)) {
            /** @var ModelsManager $modelsManager */
            $modelsManager = Di::getDefault()->get('modelsManager');
            $relations = $modelsManager->getRelations($modelName);
            $relationsToLoad = [];

            /** @var RelationInterface $relation */
            foreach ($relations as $relation) {
                $relationsToLoad[] = $relation->getOptions()['alias'];
            }
        }

        if (is_null($data)) {
            $data = $model::find();
        }

        if ($relationsToLoad) {
            $resultSet = Loader::fromResultset($data, $relationsToLoad);
        } else {
            $resultSet = $data;
        }

        $resultSet = (is_null($resultSet)) ? [] : $resultSet;
        return new Collection($resultSet, $transformer);
    }

    public static function getPage()
    {
        return Di::getDefault()->get('request')->get('page', null, 1);
    }

    public static function getLimit()
    {
        return Di::getDefault()->get('request')->get('limit', null, 20);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public static function shouldPaginate()
    {
        return (boolean)Di::getDefault()->get('request')->get('paginate', null, false);
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return Model\Query\BuilderInterface
     */
    public static function getQueryBuilder()
    {
        $calledClass = get_called_class();
        /** @var Model $model */
        $model = new $calledClass();
        return $model->getModelsManager()->createBuilder();
    }
}
