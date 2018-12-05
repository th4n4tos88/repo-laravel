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
        return view('productsAPI.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productsAPI.create');

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $token = $this->getUsersKey();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'http://localhost:8000/api/products', [
            'headers' => ['Authorization' => 'Bearer '.$token, 
            'Accept'  => 'application/json'],
            'json'    => [
                          'name' => $request->name,
                          'detail' => $request->detail
                         ]

            ]);
   
        return redirect()->route('productsAPI.index')
                         ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductAPI  $productAPI
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $productAPI = $this->requestProductWithId($request->segment(2));

        return view('productsAPI.show',compact('productAPI'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductAPI  $productAPI
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
   {
       
       $productAPI = $this->requestProductWithId($request->segment(2));

       return view('productsAPI.edit',compact('productAPI'));

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
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
        $token = $this->getUsersKey();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('PUT', 'http://localhost:8000/api/products/'.$request->segment(2), [
            'headers' => ['Authorization' => 'Bearer '.$token, 
            'Accept'  => 'application/json'],
            'json'    => [
                          'name' => $request->name,
                          'detail' => $request->detail
                         ]

            ]);
  
        return redirect()->route('productsAPI.index')
                        ->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductAPI  $productAPI
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        
            $token = $this->getUsersKey();
            $client = new \GuzzleHttp\Client();
            $res = $client->request('DELETE', 'http://localhost:8000/api/products/'.$request->segment(2), [
                'headers' => ['Authorization' => 'Bearer '.$token, 
                'Accept'  => 'application/json'],
                ]);
       
                return redirect()->route('productsAPI.index')
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
    public function requestProductWithId($id){
        

        $token=$this->getUsersKey();

        //request the product with id
        $client2 = new \GuzzleHttp\Client();
       $res=$client2->request('GET', 'http://localhost:8000/api/products/'.$id, [
       'headers' => [
       'Authorization' => 'Bearer '.$token,
       'Accept'     => 'application/json'],
       ]);


   $contents =$res->getBody();
   $arrayData=(json_decode($contents , true));
   $productAPI = $arrayData['data'];
   return $productAPI;
    }
}
