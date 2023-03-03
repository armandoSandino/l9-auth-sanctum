<?php

namespace App\Http\API\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as CustomController;
use App\Models\Product;
use Validator;
use App\Http\Resources\ProductResource;

class ProductController extends CustomController
{
    
    public function index(){
        $products = Product::all();
        return $this->sendResponse(
            ProductResource::collection($products),
            'Product retrieved successfully.'
        );
    }

    public function store( Request $request ){
        $input = $request->all();
        $validator = Validator::make( $input,[
            'name' => 'required',
            'detail' => 'required'
        ]);

        if( $validator->fails() )  {
            return $this->sendError('Validator Error.', $validator->errors());
        }
        $product = Product::create($input);

        return $this->sendResponse( new ProductResource($product),
        'Product Created Successfully.');
    }

    public function show( $id ){
        $product = Product::findOrFail( $id );
        if ( is_null( $product )  ) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse( new ProductResource($product),
        'Product retrieved successfully');
    }

    public function update(Request $request, Product $product ){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'=> 'required',
            'detail'=> 'required'
        ]);

        if ( $validator->fails() ){
            return $this->sendError('Validation Error.', $validator->errors() );
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return $this->sendResponse( new ProductResource($product), 'Product Udated successfully.');
    }

    public function destroy( Product $product ) {
        $product->delete();
        return $this->sendResponse([],'Product deleted successfully.');
    }
}
