<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\VariationResource;

/**
 * Class ProductVariationResource
 * @package App\Http\Resources
 * @OA\Schema(
 * )
 */
class ProductVariationResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=1, description="ID", property="id"),
     * @OA\Property(format="string", title="name", default="product 01", description="name", property="name"),
     * @OA\Property(format="string", title="description", default="description test", description="description", property="description")
     * @OA\Property(format="int64", title="stock", default="100", description="stock", property="stock")
     * @OA\Property(type="number", format="currency", title="price", default="50.99", description="price", property="price")
     * @OA\Property(format="string", title="attribute", default="color", description="attribute name" , property="attribute"),
     * @OA\Property(format="string", title="option", default="blue", description="attribute value ", property="option")
     *       
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->when($this->id, $this->id),
            'name' => $this->name,
            'description' => $this->description,
            'stock' => $this->stock,
            'price' => $this->price,
            'attribute' => $this->attribute,
            'option' => $this->option,
            'price' => $this->price,
        ];
    }
}
