<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\PriceChanged;
use Illuminate\Http\Request;

/**
 * Class Product
 * @package App\Models
 */
class Product extends Model
{
    use HasFactory;

    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const PRICE = 'price';
    const SUBSCRIBED_USER_IDS = 'subscribed_user_ids';
    const SUBSCRIBED = 'subscribed';

    /**
     * @var string[]
     */
    protected $fillable = [
        self::TITLE,
        self::DESCRIPTION,
        self::PRICE,
        self::SUBSCRIBED_USER_IDS
    ];

    /**
     * @param string $userId
     * @return Collection
     */
    public function getProducts(string $userId): Collection
    {
        $products = $this->all();

        foreach ($products as $product) {
            $product->subscribed = in_array($userId, (array)json_decode($product->subscribed_user_ids)) ? "+" : "";
        }
        return $products;
    }

    /**
     * @param string $productId
     * @param string $userId
     * @return Product
     */
    public function getProduct(string $productId, string $userId): Product
    {
        $product = $this->find($productId);
        if ($product->subscribed_user_ids) {
            $product->subscribed = in_array($userId, (array)json_decode($product->subscribed_user_ids)) ? "checked" : "";
        }
        return $product;
    }

    /**
     * @param string $id
     * @param Request $request
     */
    public function updateProduct(string $id, Request $request): void
    {
        $product = $this->find($id);

        $product->title = $request->get(self::TITLE);
        $product->description = $request->get(self::DESCRIPTION);

        if ($product->price != $request->get(self::PRICE))
            event(new PriceChanged($product, $request->get(self::PRICE)));

        $product->price = $request->get(self::PRICE);
        $ids = $this->handleSubscribedUsers($request, $product);
        $product->subscribed_user_ids = json_encode($ids);

        $product->save();
    }

    /**
     * @param Request $request
     */
    public function createProduct(Request $request): void
    {
        $product = new Product([
            self::TITLE => $request->get(self::TITLE),
            self::DESCRIPTION => $request->get(self::DESCRIPTION),
            self::PRICE => $request->get(self::PRICE)
        ]);
        $product->save();
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return array
     */
    private function handleSubscribedUsers(Request $request, Product $product): array
    {
        $userId = $request->user() ? $request->user()->id : null;
        $ids = (array)json_decode($product->subscribed_user_ids);
        $subscribed = $request->get(self::SUBSCRIBED);

        return $this->updateIdsArray($subscribed, $userId, $ids);
    }

    /**
     * @param bool $subscribed
     * @param $userId
     * @param array $ids
     * @return array
     */
    private function updateIdsArray(bool $subscribed, $userId, array $ids): array
    {
        if ($subscribed) {
            if (!in_array($userId, $ids)) {
                $ids[] = $userId;
                asort($ids);
            }
        } else {
            $key = array_search($userId, $ids);
            if ($key !== false) {
                unset($ids[$key]);
            }
        }
        return $ids;
    }
}
