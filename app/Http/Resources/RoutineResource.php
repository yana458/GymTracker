<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoutineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,

            'exercises' => $this->whenLoaded('exercises', function () {
                return $this->exercises->map(function ($e) {
                    return [
                        'id' => $e->id,
                        'name' => $e->name,
                        'instruction' => $e->instruction,
                        'category' => $e->category ? [
                            'id' => $e->category->id,
                            'name' => $e->category->name,
                            'icon_path' => $e->category->icon_path,
                        ] : null,
                        'pivot' => [
                            'target_sets' => $e->pivot->target_sets ?? 3,
                            'target_reps' => $e->pivot->target_reps ?? 10,
                            'rest_seconds' => $e->pivot->rest_seconds ?? 60,
                            'sequence' => $e->pivot->sequence ?? null,
                        ],
                    ];
                });
            }),
        ];
    }
}
