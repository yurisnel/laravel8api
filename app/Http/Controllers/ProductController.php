<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\ProductResource;
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
     *  summary="Products list",
     *  description="List with data of all products ",     
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
     *  summary="Get product",
     *  description="Get the specified product",   
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
     *  summary="Products create",
     *  description="Create products ",   
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
     *  summary="Remove product",
     *  description="Remove the specified product", 
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
     * Filter products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function filter(Request $request)
    {
        $input = $request->all();
        $result = $this->repo->filter($input);
        $response = ["success" => true, "data" => $result];
        return $response;
    }
}


/*
200: OK. The standard success code and default option.
201: Object created. Useful for the store actions.
204: No content. When an action was executed successfully, but there is no content to return.
206: Partial content. Useful when you have to return a paginated list of resources.
400: Bad request. The standard option for requests that fail to pass validation.
401: Unauthorized. The user needs to be authenticated.
403: Forbidden. The user is authenticated, but does not have the permissions to perform an action.
404: Not found. This will be returned automatically by Laravel when the resource is not found.
500: Internal server error. Ideally you're not going to be explicitly returning this, but if something unexpected breaks, this is what your user is going to receive.
503: Service unavailable. Pretty self explanatory, but also another code that is not going to be returned explicitly by the application.
*/