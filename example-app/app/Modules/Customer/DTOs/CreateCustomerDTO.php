<?php

namespace Modules\Customer\DTOs;

class CreateCustomerDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $password,
        public ?float $total_spent = null,
        public ?int $total_orders = null,
        public ?string $last_purchase_at = null,
        public ?string $address = null,
        public ?string $company = null,
        public ?string $status = null,
        public ?string $notes = null,
    ) {
    }
}
