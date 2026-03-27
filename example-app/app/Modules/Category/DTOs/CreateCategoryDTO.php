<?php

namespace Modules\Category\DTOs;

class CreateCategoryDTO
{
    public function __construct(
        public string $name,
        public ?int $parent_id = null
    ) {
    }
}
