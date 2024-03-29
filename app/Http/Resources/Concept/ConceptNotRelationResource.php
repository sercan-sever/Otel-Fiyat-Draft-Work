<?php

namespace App\Http\Resources\Concept;

use App\Traits\RedisStore;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class ConceptNotRelationResource extends JsonResource
{
    use RedisStore;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discounts_ = $this->cacheGet(key: 'cache:discounts:users:' . auth()->id());
        if (!empty($discounts_) && ($discounts_['concept_id'] == $this->id)) {
            $this->price = $discounts_['min_price'];
        }

        return [
            'id'            => $this->id,
            'price'         => Number::currency($this->price, in: config('app.locale_price'), locale: config('app.locale')),
            'name'          => $this->name,
            'open_for_sale' => [
                'value' => $this->open_for_sale,
                'label' => $this->open_for_sale->label()
            ],
        ];
    }
}
