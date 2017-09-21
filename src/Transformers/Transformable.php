<?php

namespace PhalconUtils\Transformers;

/**
 * Interface Transformable
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Interfaces
 */
interface Transformable
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function getModelsToLoad();
}
