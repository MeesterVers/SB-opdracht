<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function index(){
		$products = Product::paginate(12);

		return response()->json([
			'products'=>  $products
		], 201);
	}

	public function create(Request $request){

		$product = new Product();
		$product->name = $request->name;
		$product->item_code = $request->item_code;
		$product->description = $request->description;
		$product->brand = $request->brand;
		$product->category = $request->category;
		$product->price = $request->price;
		$product->image_1_url = $request->image_1_url;
		$product->stock_quantity = $request->stock_quantity;

		if ($request->image_1_url == '') {
			$product->image_1_url = 'default-product.png';
		}
		
		if ($product->save()) {
			return response()->json([
				'status'=>  'true',
				'message'=> 'Product created',
				'product'=> $product,
			], 201);
		} else {
			return response()->json([
				'status'=>  'false',
				'message'=> 'Oops failed to create product',
			], 401);
		}
	}
}
