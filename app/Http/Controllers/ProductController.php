<?php

namespace App\Http\Controllers;

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
}
