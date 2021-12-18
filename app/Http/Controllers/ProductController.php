<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductVariationResource;
use App\Http\Resources\ResponseResource;


class ProductController extends Controller
{
    private ProductRepositoryInterface $repo;

    public function __construct(ProductRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @OA\Get(
     *  path="/products",
     *  operationId="products-list",
     *  tags={"Products"},
     *  summary="Products List",
     *  description="Get list of all products  ",     
     *  @OA\Response(response="200", description="Successful operation",  @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ProductResource"))),
     *  @OA\Response(response="404", description="Bad Request")
     * )
     * @return AnonymousResourceCollection
     */

    public function index()
    {
        $result = $this->repo->getAll();
        return ProductResource::collection($result);
    }


    /**
     * @OA\Get(
     *  path="/products/{product_id}",
     *  operationId="products-get",
     *  tags={"Products"},
     *  summary="Product Get",
     *  description="Get specified product",   
     *  @OA\Parameter(name="product_id", in="path", required=true, @OA\Schema(type="integer")),  
     *  @OA\Response(response="200", description="Successful operation",  @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ProductResource"))),
     *  @OA\Response(response="404", description="Bad Request")
     * )
     * 
     */

    public function show($product_id)
    {
        $product = $this->repo->getById($product_id);
        return new ProductResource($product);
    }


    /**
     * @OA\Post(
     *  path="/products",
     *  operationId="products-create",
     *  tags={"Products"},
     *  summary="Products Create",
     *  description="Create products and varations",   
     *  @OA\RequestBody(
     *      required=true,
     *      description="Created product object",
     *       @OA\JsonContent(      
     *           ref="#/components/schemas/ProductResource"
     *       )
     * ),  
     *  @OA\Response(response="200", description="Successful operation",  @OA\JsonContent(ref="#/components/schemas/ProductResource")),
     *  @OA\Response(response="404", description="Bad Request")
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return ProductResource
     */

    public function store(Request $request)
    {
        $input = $request->all();
        $product = $this->repo->create($input);
        $resource = new ProductResource($product);
        return $resource->response()->setStatusCode(200);
    }


    /**
     * @OA\Put(
     *  path="/products/{product_id}",
     *  operationId="products-update",
     *  tags={"Products"},
     *  summary="Products Update",
     *  description="Update the specified product",   
     *  @OA\Parameter(name="product_id", in="path", required=true, @OA\Schema(type="integer")),  
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/ProductResource",
     *             )
     *         )
     * ),  
     *  @OA\Response(response="200", description="Successful operation",  @OA\JsonContent(ref="#/components/schemas/ProductResource")),
     *  @OA\Response(response="404", description="Bad Request")
     * )
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return ProductResource
     */

    public function update(Request $request,  $id)
    {
        $input = $request->all();
        $product = $this->repo->update($id, $input);
        //ProductUpdateEvent::dispatch($product);
        $resource = new ProductResource($product);
        return $resource->response()->setStatusCode(200);
    }


    /**
     * @OA\Delete(
     *  path="/products/{product_id}",
     *  operationId="products-remove",
     *  tags={"Products"},
     *  summary="Product Delete",
     *  description="Delete the specified product", 
     * @OA\Parameter(name="product_id", in="path", required=true, @OA\Schema(type="integer")),      
     *  @OA\Response(response="200", description="Successful operation",  @OA\JsonContent()),
     *  @OA\Response(response="404", description="Product Not Found")
     * )
     * @param  int  $id
     * @return ResponseResource
     */

    public function destroy($product_id)
    {
        $this->repo->delete($product_id);
        $response = ["success" => true,  "message" => \Lang::get('messages.ok_delete')];
        return new ResponseResource($response);
    }


    /**
     * @OA\Get(
     *  path="/products/filter",
     *  operationId="products-filter",
     *  tags={"Products"},
     *  summary="Product/Variations Filter",
     *  description="Get a list of products and variations filtered by characteristics and attributes ",  
     * @OA\Parameter(name="name", description="Product name", in="query", @OA\Schema(type="string")),
     * @OA\Parameter(name="description", description="Product description", in="query", @OA\Schema(type="string")),
     * @OA\Parameter(name="price", description="Price (Ex: 100) or Price Range (Ex: 50-100)", in="query", @OA\Schema(type="string")),
     * @OA\Parameter(name="stock", description="Check if you only require the products that have stock", in="query", @OA\Schema(type="boolean")),
     * @OA\Parameter(name="variations", description="Comma separated product variations (Ex: small, blue )", in="query", @OA\Schema(type="string")),
     *  @OA\Response(response="200", description="Successful operation",  @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ProductVariationResource"))),
     *  @OA\Response(response="404", description="Bad Request")
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return AnonymousResourceCollection
     */

    public function filter(Request $request)
    {
        $input = $request->all();
        $result = $this->repo->filter($input);
        return ProductVariationResource::collection($result);
    }
}
