<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'message' => 'data all product order by time',
            'data' => Product::orderBy('created_at', 'DESC')->get()
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required|numeric'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product = Product::create($request->all());
            $response = [
                'message' => 'data has been created',
                'data' => $product
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $response = [
            'message' => 'Detail data product ' . $product->title,
            'data' => $product
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required|numeric'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product->update($request->all());
            $response = [
                'message' => 'data has been updated!',
                'data' => $product
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        try {
            $response = [
                'message' => 'Data ' . $product->title . ' has been deleted!'
            ];
            $product->delete($id);

            return response()->json($response, Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo
            ]);
        }
    }

    public function search($title){
        $product = Product::where('title', 'LIKE', '%' . $title . '%')->get();
        if($product == NULL){
            return response()->json('Data not found', Response::HTTP_NOT_FOUND);
        }

        $response = [
            'message' => 'Data found',
            'data' => $product
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}
