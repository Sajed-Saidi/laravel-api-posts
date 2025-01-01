<?php

namespace App\Http\Resources;

use App\Http\Resources\PostResource;
use Carbon\Carbon;
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
            'fullName' => $this->full_name,
            'username' => $this->username,
            'email' => $this->email,
            'createdAt' => Carbon::parse($this->created_at)->toFormattedDateString(),
            'updatedAt' => Carbon::parse($this->updated_at)->toFormattedDateString(),
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
        ];
    }
}
