<?php

namespace Modules\Reporting\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardCache extends Model
{
    protected $table = 'dashboard_caches';

    protected $fillable = [
        'key',
        'total_orders',
        'total_orders_cost',
        'pending_invoices',
        'low_inventory',
        'calculated_at',
    ];

    protected $casts = [
        'low_inventory' => 'array',
        'calculated_at' => 'datetime',
    ];
}
