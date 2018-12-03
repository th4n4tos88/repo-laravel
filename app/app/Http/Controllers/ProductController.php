<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
  
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
  
        Product::create($request->all());
   
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show',compact('product'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
  
        $product->update($request->all());
  
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
  
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }

    public function getUsersKey(){

        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'http://localhost:8000/oauth/token', [
    'json' => ['username' => 'juan-so174@hotmail.com',
    'password' => '123456',
    'grant_type' => 'password',
    'client_id' => '2',
    'client_secret' => 'GkPfFDVDv6wjkQB6J83KZxmrc7PCrmL5p7I9XKJz',]]);
        $stream = $res->getBody();
        $contents = $stream->getContents();
        $validate=(json_decode($contents , true));
        return $validate['access_token']; 

    }
    public function getProductsApi(){
        $token = $this->getUsersKey();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://localhost:8000/api/products', [
            'headers' => ['Authorization' => 'Bearer '.$token, 
                                'Accept' => 'application/json']]);
            $stream = $res->getBody();
            $contents = $stream->getContents();
            $products=(json_decode($contents , true));
            return $products; 
    }

}
