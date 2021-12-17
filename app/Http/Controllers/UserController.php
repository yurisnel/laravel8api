<?php

namespace App\Http\Controllers;

use App\Model\UserSubscription;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use \Illuminate\Support\Facades\Validator;
use App\Exceptions\HttpException;


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
     * Subscribe user to product with stock
     *
     * @param  int  $user_id
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
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
            throw new HttpException(\Lang::get('messages.input_error'), Response::HTTP_BAD_REQUEST, $validator->errors()->toArray());
        } else {
            $result = UserSubscription::create($input);
        }
        $response = ["success" => true, "data" => $result];
        return response()->json($response);
    }
}
