<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;

class FilterTest extends BoolTest
{
    protected function newBooleanClass()
    {
        return new Filter();
    }
}
