<?php

namespace PhalconUtils\Templating;

/**
 * Interface TemplatingEngineInterface
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Templating
 */
interface TemplatingEngineInterface
{
    public function renderTemplate($template, array $data);
}
