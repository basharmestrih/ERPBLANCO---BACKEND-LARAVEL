<?php

namespace Modules\Customer\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\DTOs\CreateCustomerDTO;
use Modules\Customer\Models\Customer;

class CustomerService
{
    public function getAll(): Collection
    {
        return Customer::all();
    }

    public function create(CreateCustomerDTO $dto): Customer
    {
        return Customer::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'password' => Hash::make($dto->password),
            'total_spent' => $dto->total_spent,
            'total_orders' => $dto->total_orders,
            'last_purchase_at' => $dto->last_purchase_at,
            'address' => $dto->address,
            'company' => $dto->company,
            'status' => $dto->status,
            'notes' => $dto->notes,
        ]);
    }

    public function delete(Customer $customer): void
    {
        $customer->delete();
    }
}
