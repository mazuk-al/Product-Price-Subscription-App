<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\PriceChanged;

class Product extends Model
{
    use HasFactory;

    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const PRICE = 'price';
    const SUBSCRIBED_USER_IDS = 'subscribed_user_ids';
    const SUBSCRIBED = 'subscribed';

    protected $fillable = [
        self::TITLE,
        self::DESCRIPTION,
        self::PRICE,
        self::SUBSCRIBED_USER_IDS
    ];

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProducts($userId) {
        $products = $this->all();

        foreach ($products as $product) {
            $product->subscribed = in_array($userId, (array)json_decode($product->subscribed_user_ids)) ? "+" : "";
        }
        return $products;
    }

    /**
     * @param string $productId
     * @param string $userId
     * @return mixed
     */
    public function getProduct(string $productId, string $userId) {
        $product = $this->find($productId);
        if ($product->subscribed_user_ids) {
            $product->subscribed = in_array($userId, (array)json_decode($product->subscribed_user_ids)) ? "checked" : "";
        }
        return $product;
    }

    /**
     * @param $id
     * @param $request
     */
    public function updateProduct($id, $request) {

//        echo "</pre>";
//        print_r($request->user()); die;


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
     * @param $request
     */
    public function createProduct($request) {
        $product = new Product([
            self::TITLE => $request->get(self::TITLE),
            self::DESCRIPTION => $request->get(self::DESCRIPTION),
            self::PRICE => $request->get(self::PRICE)
        ]);
        $product->save();
    }

    /**
     * @param $request
     * @param $product
     * @return array
     */
    private function handleSubscribedUsers($request, $product): array
    {
        $userId = $request->user() ? $request->user()->id : null;

        $ids = (array)json_decode($product->subscribed_user_ids);
        $subscribed = $request->get(self::SUBSCRIBED);
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
