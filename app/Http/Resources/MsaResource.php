<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MsaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'rating' => $this->rating,
            'description' => $this->description,
            'genre' => $this->genre,
            'updatedAt' => $this->updated_at,
            'createdAt' => $this->created_at, 
        ];
    }
}
