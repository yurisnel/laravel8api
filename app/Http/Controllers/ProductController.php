<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProductRepositoryInterface;
use App\Model\Product;
use App\Model\AttributeOption;

class ProductController extends Controller
{
    var $status = 200;

    private ProductRepositoryInterface $repo;

    public function __construct(ProductRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function test($id = 1)
    {

        $result = AttributeOption::with("attribute")->get();

        //$result = Product::find($id)->productAttributeValue()->where("price", ">", 600)->get(); 
        //Debugbar::enable();

        return $result;
    }

    /**
     * Display a listing of the products
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->repo->getAll();
        $response = ["success" => true, "data" => $result];
        return $response;
    }

    /**
     * Store a new products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $result = $this->repo->create($input);
        $response = ["success" => true, "message" => \Lang::get('messages.ok_store'), "data" => $result];
        return response()->json($response, $this->status);
    }

    /**
     * Display the specified product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->repo->getById($id);
        $response = ["success" => true, "data" => $result];
        return response()->json($response, $this->status);
    }

    /**
     * Update the specified product
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $input = $request->all();
        $result = $this->repo->update($id, $input);
        $response = ["success" => true, "message" => \Lang::get('messages.ok_store'), "data" => $result];
        return response()->json($response, $this->status);
    }
    /**
     * Remove the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repo->delete($id);
        $resp = ["success" => true,  "message" => \Lang::get('messages.ok_delete')];
        return response()->json($resp, $this->status);
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