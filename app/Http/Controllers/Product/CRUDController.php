<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CRUDController
 * @package App\Http\Controllers\Product
 */
class CRUDController extends Controller
{
    const VALIDATION_ARRAY = [
        Product::TITLE => 'required',
        Product::PRICE => 'required'
    ];

    /**
     * @var Product
     */
    private Product $product;

    /**
     * CRUDController constructor.
     * @param Product $product
     */
    public function __construct(
        Product $product
    ) {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(Auth::check()) {
            $products = $this->product->getProducts($request->user()->id);
            return view('products.index', compact('products'));
        }
        return $this->redirectToLogin();
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if(Auth::check()) {
            return view('products.create');
        }
        return $this->redirectToLogin();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(self::VALIDATION_ARRAY);
        $this->product->createProduct($request);
        return redirect('/products')->with('success', 'Product saved!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if(Auth::check()) {
            $product = $this->product->getProduct($id, $request->user()->id);
            return view('products.edit', compact('product'));
        }
        return $this->redirectToLogin();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(self::VALIDATION_ARRAY);
        $this->product->updateProduct($id, $request);
        return redirect('/products')->with('success', 'Product updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect('/products')->with('success', 'Product deleted!');
    }
}
