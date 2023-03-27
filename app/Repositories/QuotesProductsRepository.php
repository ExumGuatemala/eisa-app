<?php

namespace App\Repositories;

use App\Models\QuotesProducts;

class QuotesProductsRepository
{
    public function allForQuote($quoteId)
    {
        return QuotesProducts::where('quote_id', $quoteId)->get();
    }

}
