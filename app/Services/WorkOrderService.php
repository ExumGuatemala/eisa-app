<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\WorkOrder;
use App\Models\WorkOrderState;

use App\Services\QuoteService;

use App\Enums\QuoteStateEnum;

class WorkOrderService
{
    protected $quoteService;

    public function __construct()
    {
        $this->quoteService = new QuoteService;
    }
    
    public function saveWorkOrder($description, $quote_key, $start_date, $end_date)
    {
        $work_order = new WorkOrder();
        $work_order->description = $description;
        $work_order->quote_id = $quote_key;
        $work_order->state = WorkOrderState::orderBy('order', "ASC")->first()->name;;
        $work_order->start_date = $start_date;
        $work_order->end_date = $end_date;
        $work_order->save();
    }

    public function showCreateButton(Quote $quote)
    {
        //Tengo que devolver true, cuando no quiera mostrarlo 
        if(QuoteStateEnum::CREATED == $quote->state AND !$this->alreadyExists($quote->key))
        {
            return false;
        }

        if(QuoteStateEnum::CREATED != $quote->state)
        {
            return true;
        }

        return true;
    }

    public function alreadyExists($order_key)
    {
        return WorkOrder::where('quote_id', $order_key)->first();
    }

    public function getNextOrderStatus($work_order_state)
    {   
        $current_state = WorkOrderState::whereName($work_order_state)->first();
        $next_state = WorkOrderState::whereOrder($current_state->order + 1)->first();
        return $next_state ? $next_state->name : $current_state->name;
    }

    public function changeToNextOrderStatus($work_order_id, $work_order_state)
    {
        $next_state = $this->getNextOrderStatus($work_order_state);
        $work_order = WorkOrder::whereId($work_order_id)->first();
        $work_order->state = $next_state;
        $work_order->save();
    }

    public function getLastWorkOrderState()
    {
        return WorkOrderState::orderBy('order', "DESC")->first()->name;
    }
}