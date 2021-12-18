<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Model\Product;
use App\Exceptions\ExceptionData;
use \Illuminate\Http\Response;
use \Illuminate\Support\Facades\Validator;

class ProductRepository implements ProductRepositoryInterface
{
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'stock' => 'required',
        ];
    }

    public function getAll()
    {
        return Product::all();
    }

    public function getById($itemId)
    {
        //$product = Product::with('productAttributeValue')->find($itemId);
        $product = Product::find($itemId);
        if (!$product)
            throw new \Exception(\Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        else
            return $product;
        //return Product::findOrFail($itemId);
    }

    public function create(array $input)
    {
        $validator = Validator::make($input, $this->rules());
        if ($validator->fails()) {
            throw new ExceptionData(\Lang::get('messages.input_error'), Response::HTTP_BAD_REQUEST, $validator->errors()->toArray());
        } else {
            $product = Product::create($input);

            /*if (!empty($input["attributes"])) {
                $this->createVariations($product, $input["attributes"]);
            }*/
            return  $product;
        }
    }

    public function update($itemId, array $input)
    {
        if (empty($input)) {
            throw new \Exception(\Lang::get('messages.input_error'), Response::HTTP_BAD_REQUEST);
        } else {

            $product = Product::find($itemId);
            if ($product) {
                $product->update($input);
                return $product;
            } else
                throw new \Exception(\Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
    }


    public function delete($itemId)
    {
        $product = Product::find($itemId);
        if ($product)
            return  Product::destroy($itemId);
        else
            throw new ExceptionData(\Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
    }


    public function filter($filter)
    {
        $query = \DB::table('products as p');
        $query->select('p.id', 'p.name', 'p.description', 'p.stock', 'a.name as attribute', 'ao.name as option', \DB::Raw('IFNULL(v.price, p.price) as price'));
        $query->leftJoin('product_attribute_values as v', 'v.product_id', '=', 'p.id');
        $query->leftJoin('attribute_options as ao', 'ao.id', '=', 'v.attribute_options_id');
        $query->leftJoin('attributes as a', 'a.id', '=', 'ao.attribute_id');
        $query->orderBy('p.id');

        if (isset($filter['name'])) {
            $query->where('p.name', 'LIKE', "%".$filter['name']."%");
        }
        if (isset($filter['description'])) {
            $query->where('p.description', 'LIKE', "%".$filter['description']."%");
        }
        if (isset($filter['stock']) && $filter['stock'] == 'true') {
            $query->where('p.stock', '>', 0);
        }

        if (isset($filter['price'])) {
            $priceRaw = \DB::Raw('IFNULL(v.price, p.price)');
            $price = explode("-", $filter['price']);
            if (is_array($price) && count($price) == 2) {
                $query->whereBetween($priceRaw, [$price[0], $price[1]]);
            } else {
                $query->where($priceRaw, $price);
            }
        }

        if (isset($filter['variations'])) {
            $options_attributes_name = explode(",", $filter['variations']);
            $query->whereIn('ao.name', $options_attributes_name);
        }
        //\DB::enableQueryLog(); // Enable query log
        $result = $query->get();
        // dd(\DB::getQueryLog()[0]); // Show results of log       
        return $result;
    }
}
