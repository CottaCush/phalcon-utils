<?php

namespace PhalconUtils\Templating\Helpers;

use Handlebars\Context;
use Handlebars\Helper as HelperInterface;
use Handlebars\Template;
use PhalconUtils\Util\TextUtils;

/**
 * Format number to Nigerian Naira.
 *
 * Usage:
 * ```handlebars
 *   {{formatToNaira value}}
 * ```
 *
 * Arguments:
 *  - "value": must be valid numeric value
 *
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class FormatToNairaHelper implements HelperInterface
{

    /**
     * {@inheritdoc}
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $arguments = $template->parseArguments($args);
        if (count($arguments) != 1) {
            throw new \InvalidArgumentException(
                '"formatToNaira" helper expects an argument.'
            );
        }

        return TextUtils::formatToNaira($context->get($arguments[0]));
    }
}
