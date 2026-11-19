<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->whenHas('email_verified_at'),
            'avatar_path' => $this->avatar_path,
            'avatar_url' => $this->avatar_url,
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),
        ];
    }
}
