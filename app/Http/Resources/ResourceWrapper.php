<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWrapper extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status' => 'success',
            'code' => 200,
            'message' =>  isset($this['message']) ? $this['message'] : '',
            'toast' => isset($this['toast']) ? $this['toast'] : false,
        ];
    }
}
