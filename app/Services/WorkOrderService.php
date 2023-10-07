<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\WorkOrder;

use App\Services\QuoteService;

use App\Enums\QuoteTypeEnum;

class WorkOrderService
{
    protected $quoteService;

    public function __construct()
    {
        $this->quoteService = new QuoteService;
    }
    
    public function saveWorkOrder($client_name, $description, $quote_key, $start_date, $deadline)
    {
        $work_order = new WorkOrder();
        $work_order->description = $description;
        $work_order->quote_id = $quote_key;
        $work_order->start_date = $start_date;
        $work_order->deadline = $deadline;
        $work_order->save();
    }

    public function showCreateButton(Quote $quote)
    {
        return !(QuoteTypeEnum::IN_PROGRESS != $quote->status AND !$this->alreadyExists($quote->key));
    }

    public function alreadyExists($order_key)
    {
        return WorkOrder::where('quote_id', $order_key)->first();
    }
}