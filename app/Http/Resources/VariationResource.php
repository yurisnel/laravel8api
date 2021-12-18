<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class VariationResource
 * @package App\Http\Resources
 * @OA\Schema(
 * )
 */
class VariationResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=1, description="ID", property="id"),
     * @OA\Property(format="string", title="attribute", default="color", description="attribute name" , property="attribute"),
     * @OA\Property(format="string", title="option", default="blue", description="attribute value ", property="option")
     * @OA\Property(format="float", title="price", default="50.99", description="price", property="price")
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            'attribute' => $this->option['attribute'],
            'option' => $this->option['name'],
            'price' => $this->price,
        ];
    }
}
