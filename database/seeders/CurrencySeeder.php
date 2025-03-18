<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            // 🟢 أولًا: الريال اليمني
            ['name' => 'الريال اليمني', 'code' => 'YER', 'symbol' => '﷼'],

            // 🟠 ثانيًا: عملات الخليج العربي
            ['name' => 'الريال السعودي', 'code' => 'SAR', 'symbol' => '﷼'],
            ['name' => 'الدرهم الإماراتي', 'code' => 'AED', 'symbol' => 'د.إ'],
            ['name' => 'الدينار الكويتي', 'code' => 'KWD', 'symbol' => 'د.ك'],
            ['name' => 'الريال العماني', 'code' => 'OMR', 'symbol' => '﷼'],
            ['name' => 'الريال القطري', 'code' => 'QAR', 'symbol' => '﷼'],
            ['name' => 'الدينار البحريني', 'code' => 'BHD', 'symbol' => '.د.ب'],

            // 🔵 ثالثًا: العملات العالمية
            ['name' => 'الدولار الأمريكي', 'code' => 'USD', 'symbol' => '$'],
            ['name' => 'اليورو', 'code' => 'EUR', 'symbol' => '€'],
            ['name' => 'الجنيه الإسترليني', 'code' => 'GBP', 'symbol' => '£'],
            ['name' => 'الين الياباني', 'code' => 'JPY', 'symbol' => '¥'],
            ['name' => 'الدولار الكندي', 'code' => 'CAD', 'symbol' => 'C$'],
            ['name' => 'الدولار الأسترالي', 'code' => 'AUD', 'symbol' => 'A$'],
            ['name' => 'الفرنك السويسري', 'code' => 'CHF', 'symbol' => 'CHF'],
            ['name' => 'اليوان الصيني', 'code' => 'CNY', 'symbol' => '¥'],
            ['name' => 'الروبية الهندية', 'code' => 'INR', 'symbol' => '₹'],
        ];

        // إدراج البيانات في قاعدة البيانات
        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']], // تجنب التكرار
                $currency
            );
        }
    }
}
