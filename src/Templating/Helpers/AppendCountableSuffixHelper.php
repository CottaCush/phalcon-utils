<?php

namespace PhalconUtils\Templating\Helpers;

use Handlebars\Context;
use Handlebars\Helper as HelperInterface;
use Handlebars\Template;
use PhalconUtils\Util\TextUtils;

/**
 * Append a countable suffix to a number.
 *
 * Usage:
 * ```handlebars
 *   {{appendCountableSuffix value suffix suffixPluralForm}}
 * ```
 *
 * Arguments:
 *  - "value": must be valid numeric value
 *
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class AppendCountableSuffixHelper implements HelperInterface
{

    /**
     * {@inheritdoc}
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        $arguments = $template->parseArguments($args);
        if (count($arguments) != 3) {
            throw new \InvalidArgumentException(
                '"appendCountableSuffix" helper expects exactly 3 arguments.'
            );
        }

        return TextUtils::appendCountableSuffix($context->get($arguments[0]), $arguments[1], $arguments[2]);
    }
}
