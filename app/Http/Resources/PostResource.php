<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed user
 * @property mixed title
 * @property mixed description
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
