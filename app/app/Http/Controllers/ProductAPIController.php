<?php

namespace App\Http\Controllers;

use App\ProductAPI;
use Illuminate\Http\Request;

class ProductAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = $this->getUsersKey();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://localhost:8000/api/products', [
            'headers' => ['Authorization' => 'Bearer '.$token, 
                                'Accept' => 'application/json']]);
            $stream = $res->getBody();
            $contents = $stream->getContents();
            $productsResponse=(json_decode($contents , true));
            $products=$productsResponse['data'];
        return view('productsAPI.index',compact('products'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductAPI  $productAPI
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAPI $productAPI)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductAPI  $productAPI
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductAPI $productAPI)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductAPI  $productAPI
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductAPI $productAPI)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductAPI  $productAPI
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAPI $productAPI)
    {
        //
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
}
