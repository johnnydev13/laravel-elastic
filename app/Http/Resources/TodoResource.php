<?php

namespace App\Http\Resources;

use App\Traits\EnumTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\TodoStatusEnum;

class TodoResource extends JsonResource
{
    use EnumTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $statusValue = $this->getKeysByValues(TodoStatusEnum::class);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'due_on' => $this->due_on,
            'status' => $statusValue[$this->status],
            'user' => UserResource::make($this->user),
        ];
    }
}
