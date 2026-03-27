<?php

namespace Modules\Unit\DTOs;

class CreateUnitDTO
{
    public function __construct(
        public string $name,
        public string $symbol
    ) {
    }
}
