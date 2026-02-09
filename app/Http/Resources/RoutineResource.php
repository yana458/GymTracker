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
                return $this->exercises->map(function ($ex) {
                    return [
                        'id' => $ex->id,
                        'name' => $ex->name,
                        'instruction' => $ex->instruction,
                        'category_id' => $ex->category_id,

                        // pivote “al mismo nivel” (como pide el reto)
                        'sequence' => $ex->pivot->sequence,
                        'target_sets' => $ex->pivot->target_sets,
                        'target_reps' => $ex->pivot->target_reps,
                        'rest_seconds' => $ex->pivot->rest_seconds,
                    ];
                });
            }),
        ];
    }
}
