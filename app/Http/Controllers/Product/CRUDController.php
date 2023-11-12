<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application as ApplicationContracts;

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
     * @param Request $request
     * @return View|Application|Factory|ApplicationContracts|RedirectResponse
     */
    public function index(Request $request): View|Application|Factory|ApplicationContracts|RedirectResponse
    {
        if(Auth::check()) {
            $products = $this->product->getProducts($request->user()->id);
            return view('products.index', compact('products'));
        }
        return $this->redirectToLogin();
    }

    /**
     * Show the form for creating a new resource.
     * @return ApplicationContracts|Factory|View|Application|RedirectResponse
     */
    public function create(): Application|View|Factory|RedirectResponse|ApplicationContracts
    {
        if(Auth::check()) {
            return view('products.create');
        }
        return $this->redirectToLogin();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Application|Redirector|RedirectResponse|ApplicationContracts
     */
    public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContracts
    {
        $request->validate(self::VALIDATION_ARRAY);
        $this->product->createProduct($request);
        return redirect('/products')->with('success', 'Product saved!');
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param string $id
     * @return View|Application|Factory|ApplicationContracts|RedirectResponse
     */
    public function edit(Request $request, string $id): View|Application|Factory|ApplicationContracts|RedirectResponse
    {
        if(Auth::check()) {
            $product = $this->product->getProduct($id, $request->user()->id);
            return view('products.edit', compact('product'));
        }
        return $this->redirectToLogin();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Application|Redirector|RedirectResponse|ApplicationContracts
     */
    public function update(Request $request, string $id): Application|Redirector|RedirectResponse|ApplicationContracts
    {
        $request->validate(self::VALIDATION_ARRAY);
        $this->product->updateProduct($id, $request);
        return redirect('/products')->with('success', 'Product updated!');
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return Application|Redirector|RedirectResponse|ApplicationContracts
     */
    public function destroy(string $id): Application|Redirector|RedirectResponse|ApplicationContracts
    {
        $product = Product::find($id);
        $product->delete();

        return redirect('/products')->with('success', 'Product deleted!');
    }
}
