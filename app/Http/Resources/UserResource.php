<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    private array $list = [];
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  $this->map(function ($item){
            return [
                "name" => $item['name'],
                "birthdate" => $item['birthdate'],
                "country" => $item->Country()?->first()?->name,
            ];
        })->toArray();
    }
}
