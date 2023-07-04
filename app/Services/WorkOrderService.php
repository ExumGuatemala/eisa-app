<?php

namespace App\Services;

use App\Models\WorkOrder;

class WorkOrderService
{
    public function saveWorkOrder($client_name, $description, $quote_key, $start_date, $deadline)
    {
        $work_order = new WorkOrder();
        $work_order->client_name = $client_name;
        $work_order->description = $description;
        $work_order->order_key = $quote_key;
        $work_order->start_date = $start_date;
        $work_order->deadline = $deadline;
        $work_order->save();
    }

    public function countByKey($order_key){
        return WorkOrder::where('order_key', $order_key)->get()->count();
    }
}