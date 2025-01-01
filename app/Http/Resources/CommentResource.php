<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'postId' => $this->post_id,
            'userId' => $this->user_id,
            'content' => $this->content,
            'user' => new UserResource($this->whenLoaded('user')),
            'createdAt' => ($this->created_at)->format('Y-m-d H:i:s'),
            'updatedAt' => ($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
