<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

/**
 * Class PriceChanged
 * @package App\Events
 */
class PriceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Product
     */
    public Product $product;
    /**
     * @var float
     */
    public float $newPrice;

    /**
     * Create a new event instance.
     */
    public function __construct(
        Product $product,
        float $newPrice
    ) {
        $this->product = $product;
        $this->newPrice = $newPrice;
    }
}
