<?php

namespace PhalconUtils\Templating;

use Handlebars\Handlebars;

/**
 * Class HandlebarsTemplatingEngine
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Templating
 */
class HandlebarsTemplatingEngine extends Handlebars implements TemplatingEngineInterface
{
    public function __construct(array $options = array())
    {
        if (!array_key_exists('helpers', $options)) {
            $options['helpers'] = new HandleBarsHelpers();
        }

        parent::__construct($options);
    }

    public function renderTemplate($template, array $data)
    {
        return $this->render($template, $data);
    }
}
