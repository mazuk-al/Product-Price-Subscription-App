<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PriceChanged
 * @package App\Events
 */
class PriceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $newPrice;
    /**
     * Create a new event instance.
     */
    public function __construct(
        $product,
        $newPrice
    ) {
        $this->product = $product;
        $this->newPrice = $newPrice;
    }
}
