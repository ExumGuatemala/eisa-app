<?php

namespace App\Services;

use App\Models\QuotesProducts;

use App\Models\ProductsPriceTypes;
use App\Repositories\ProductsPriceTypesRepository;
use App\Services\QuoteService;

class QuotesProductsService
{
    protected $productsPriceTypesRepository;
    protected $quoteService;

    public function __construct()
    {
        $this->productsPriceTypesRepository = new ProductsPriceTypesRepository(new ProductsPriceTypes);
        $this->quoteService = new QuoteService;
    }

    public function updateAllPrices($quoteId, $pricetypeId)
    {
        $quotesproducts = QuotesProducts::all();
        foreach($quotesproducts as $qp){
            $qp->price = ($qp->totalm != 0 ? $qp->totalm : $qp->quantity) * $this->productsPriceTypesRepository->getProductPrice($pricetypeId, $qp->product_id);
            $qp->save();
        }
        $this->quoteService->updateTotal($quoteId);
    }

}
