# Sales Module — Orders API

**Common requirements**  
- All endpoints live under `Route::middleware(['auth:sanctum'])`.  
- ERP/admin users must pass the policy checked in `Modules\Sales\Policies\OrderPolicy` (`view order`, `create order`, `approve order`).  
- Customer tokens use the `customer` guard for the `index`/`store` routes shown below.

**GET /api/orders**  
- Authorization: `Bearer <erp-user-token>` (requires `view order`).  
- Returns `Order::with(['items.product', 'customer', 'creator', 'approver'])`.  
- Sample response: `[{ "id": 331, "total_amount": 295.5, "state": "pending", "items": [ ... ], "customer": { ... } }]`.  
- Errors: `401 Unauthorized`, `403 Forbidden`.

**POST /api/orders**  
- Accepts JSON payload (`Content-Type: application/json`).  
- Customer flow: omit `customer_id`; the customer ID is read from `auth()->guard('customer')`.  
- ERP/admin: send `customer_id` and have `create order` permission.  
- Payload:

```json
{
  "customer_id": 27,
  "items": [
    { "product_id": 190, "quantity": 3 }
  ]
}
```

- Response (201): order with nested `items.product`, `customer`, `creator`, `approver` (same as `OrderService::getAll`).  
- Errors: `401`, `403`, `422` when `items` is missing/invalid, `500` when stock is insufficient.

**POST /api/orders/{order}/approve**  
- Authorization: `Bearer <erp-user-token>` with `approve order` permission and target order in `Pending` state.  
- No request body.  
- Response: updated order, includes `state: "approved"` and `approved_by`.  
- Errors: `401`, `403` (policy failure or already approved), `404` if order missing, `500` for unexpected failures.
