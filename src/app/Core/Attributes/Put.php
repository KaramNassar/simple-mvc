<?php

declare(strict_types=1);

namespace App\Core\Attributes;

use App\Core\Enums\HttpMethod;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Put extends Route
{

    public function __construct(string $path)
    {
        parent::__construct($path, HttpMethod::Put);
    }

}