<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class ResponseResource
 * @package App\Http\Resources
 * @OA\Schema(
 * )
 */
class ResponseResource extends JsonResource
{
    /**
     * @OA\Property(format="string", title="success", default="true", description="Request status", property="success"),
     * @OA\Property(format="string", title="message", default="Operation finished", description="Request description (optional)", property="message")
     * @OA\Property(format="string", title="data", default="[]", description="Request response(optional)", property="data")
     *       
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
