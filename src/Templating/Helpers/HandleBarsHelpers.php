<?php

namespace PhalconUtils\Templating\Helpers;

use JustBlackBird\HandlebarsHelpers\Helpers as JustBlackBirdHelpers;

/**
 * Class HandleBarsHelpers
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Templating
 */
class HandleBarsHelpers extends JustBlackBirdHelpers
{
    protected function addDefaultHelpers()
    {
        parent::addDefaultHelpers();

        $this->add('formatToNaira', new FormatToNairaHelper());
        $this->add('appendCountableSuffix', new AppendCountableSuffixHelper());
    }
}
