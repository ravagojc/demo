<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;



class OrderController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->only('product_id', 'quantity');

        //Validate data
        $validator = Validator::make($data, [
            'product_id' => 'required|int',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $product = Product::find($request->product_id);

        if($product){
            if($product->quantity>=$request->quantity){
                //Request is valid, update product
                $product = $product->update([
                    'quantity' => $product->quantity-$request->quantity
                ]);

                //Create order
                $order = $this->user->orders()->create([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'You have succuessfully ordered this product'
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Unsuccessful order due to insufficient stock of the product.'
                ], Response::HTTP_OK);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], Response::HTTP_OK);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
