<?php

namespace App\Http\Controllers;

use App\Model\UserSubscription;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use \Illuminate\Support\Facades\Validator;
use App\Exceptions\ExceptionData;
use App\Http\Resources\ResponseResource;


class UserController extends Controller
{
    public function rulesSubscribe()
    {
        return [
            'user_id' => 'required',
            'product_id' => 'required'
        ];
    }

    /**
     * @OA\Get(
     *  path="/users/subscribe/{user_id}/{product_id}",
     *  operationId="user-subscribe",
     *  tags={"Users"},
     *  summary="User Subscription",
     *  description="Returns subscription data",
     *  @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(type="integer")),
     *  @OA\Parameter(name="product_id", in="path", required=true, @OA\Schema(type="integer")),
     *  @OA\Response(response="200", description="Successful operation",  @OA\JsonContent(ref="#/components/schemas/ResponseResource")),
     *  @OA\Response(response="404", description="Bad Request")
     * )
     * @param  int  $user_id
     * @param  int  $product_id
     * @return ResponseResource
     */

    public function subscribe($user_id, $product_id)
    {
        $input = [
            'user_id' => $user_id,
            'product_id' => $product_id
        ];
        $result = false;
        $validator = Validator::make($input, $this->rulesSubscribe());
        if ($validator->fails()) {
            throw new ExceptionData(\Lang::get('messages.input_error'), Response::HTTP_BAD_REQUEST, $validator->errors()->toArray());
        } else {
            $result = UserSubscription::create($input);
        }
        $response = ["success" => true, "data" => $result, "message" => \Lang::get('messages.ok_store')];
        return new ResponseResource($response);
    }
}
