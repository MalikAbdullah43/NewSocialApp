<?php

namespace App\Http\Resources;
use App\Models\User as User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PostResource;
class UserPosts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'posts' => PostResource::collection($this->Post()->get()),
        ];
    }
}
