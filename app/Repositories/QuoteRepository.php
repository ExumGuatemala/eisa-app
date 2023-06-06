<?php

namespace App\Repositories;

use App\Models\Quote;
use App\Models\Client;
use App\Models\ProductsPriceTypes;
use App\Repositories\QuotesProductsRepository;

class QuoteRepository
{
    protected $quotesProductsRepository;
    public function __construct()
    {
        $this->quotesProductsRepository = new QuotesProductsRepository;
        // $this->productsPriceTypesRepository = new ProductsPriceTypesRepository;
        // $this->quoteRepository = new QuoteRepository;
        // $this->quotesProductsRepository = new QuotesProductsRepository;
    }
    public function all()
    {
        return Quote::all();
    }

    public function find(int $id)
    {
        return Quote::find($id);
    }

    public function get($id)
    {
        return Quote::where('id', $id)->get();
    }

    public function updateById(int $id, array $attributes): bool
    {
        $obj = Quote::find($id);

        foreach ($attributes as $key => $value) {
            $obj->{$key} = $value;
        }

        return $obj->save();
    }

    public function updateProductPrices($priceTypeId,$productId, $newPrice){
        $quotes = Quote::where('pricetype_id', $priceTypeId)
        ->where('status','En Progreso')
        ->get();
        foreach ($quotes as $quote) {
            $this->quotesProductsRepository->updatesProductsPrices($quote->id, $newPrice, $productId);
        }
        return $quotes;
    }
}
