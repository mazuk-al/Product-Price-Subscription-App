<?php

namespace App\Listeners;

use App\Events\PriceChanged;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserMail;
use App\Models\User;

/**
 * Class PriceChangedListener
 * @package App\Listeners
 */
class PriceChangedListener
{
    /**
     * Handle the event.
     */
    public function handle(PriceChanged $event): void
    {
        $product = $event->product;
        $subscribedUserIds = (array)json_decode($product->subscribed_user_ids);
        foreach ($subscribedUserIds as $subscribedUserId) {
            $user = User::find($subscribedUserId);
            if (!is_null($user) && in_array($user->id, $subscribedUserIds)) {
                $content = $this->prepareContentArray($user, $product, $event->newPrice);
                Mail::to($user->email)->send(new UserMail($content));
            }
        }
    }

    /**
     * @param $user
     * @param $product
     * @param $newPrice
     * @return array
     */
    private function prepareContentArray($user, $product, $newPrice): array
    {
        return [
            'name' => $user->name,
            'product_id' => $product->id,
            'product_title' => $product->title,
            'old_price' => $product->price,
            'new_price' => $newPrice
        ];
    }
}
