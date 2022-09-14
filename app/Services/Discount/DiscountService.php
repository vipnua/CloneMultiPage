<?php

namespace App\Services\Discount;

use App\Model\Discount;

class DiscountService
{
    private $allowed = [
      'profile',
      'share',
      'date',
      'price'
    ];
    public function createDiscount($data) {
        $dataFilter = array_filter(
            $data,
            function ($key) {
                return in_array($key, $this->allowed);
            },
            ARRAY_FILTER_USE_KEY
        );
        $discount = Discount::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'max_use' => $data['max_use'],
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
            'data' => $dataFilter,
        ]);
        return $discount;
    }

    public function updateDiscount($id, $data) {
        $dataFilter = array_filter(
            $data,
            function ($key) {
                return in_array($key, $this->allowed);
            },
            ARRAY_FILTER_USE_KEY
        );
        $discount = Discount::find($id)->update([
            'name' => $data['name'],
            'code' => $data['code'],
            'max_use' => $data['max_use'],
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
            'data' => $dataFilter,
        ]);
        return $discount;
    }

    public function deleteDiscount($id) {
        $discount = Discount::find($id)->delete();
        return $discount;
    }
}
