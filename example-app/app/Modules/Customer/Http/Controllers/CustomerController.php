<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\DTOs\CreateCustomerDTO;
use Modules\Customer\Models\Customer;
use Modules\Customer\Services\CustomerService;

class CustomerController extends Controller
{
    public function index(CustomerService $customerService)
    {
        return response()->json($customerService->getAll());
    }

    public function store(Request $request, CustomerService $customerService)
    {
        $payload = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:255|unique:customers,phone',
            'password' => 'required|string|min:6',
            'total_spent' => 'nullable|numeric|min:0',
            'total_orders' => 'nullable|integer|min:0',
            'last_purchase_at' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'company' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $dto = new CreateCustomerDTO(
            name: $payload['name'],
            email: $payload['email'],
            phone: $payload['phone'],
            password: $payload['password'],
            total_spent: 0,
            total_orders: 0,
            last_purchase_at:null,
            address: $payload['address'] ?? null,
            company: $payload['company'] ?? null,
            status: $payload['status'] ?? null,
            notes: $payload['notes'] ?? null,
        );

        $customer = $customerService->create($dto);
        //$token = $customer->createToken('CustomerToken')->plainTextToken;


        return response()->json([
            'message' => 'Customer created successfully',
            'customer' => $customer,
            //'token'    => $token,
        ], 201);
    }

    public function destroy(Customer $customer, CustomerService $customerService)
    {
        $customerService->delete($customer);

        return response()->json([
            'message' => 'Customer deleted successfully',
        ]);
    }
}
