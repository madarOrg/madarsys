<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypesSeeder extends Seeder
{
    public function run()
    {
        $payment_types = [ 
            ['name' => 'نقدي', 'created_user' => 1, 'updated_user' => 1],
            ['name' => 'أجل', 'created_user' => 1, 'updated_user' => 1], 
            ['name' => 'بطاقة ائتمانية', 'created_user' => 1, 'updated_user' => 1],
            ['name' => 'شيك', 'created_user' => 1, 'updated_user' => 1], 
        ];
        

        foreach ($payment_types as $type) {
            PaymentType::create($type);
        }
    }
}
