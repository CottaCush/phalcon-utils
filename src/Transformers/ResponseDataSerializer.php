<?php

namespace PhalconUtils\Transformers;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class ResponseDataSerializer
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Fractal
 */
class ResponseDataSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        if ($resourceKey == null) {
            return $data;
        }

        return [$resourceKey ?: 'data' => $data];
    }

    public function item($resourceKey, array $data)
    {
        if ($resourceKey == null) {
            return $data;
        }

        return [$resourceKey ?: 'data' => $data];
    }

    public function null()
    {
        return null;
    }
}
