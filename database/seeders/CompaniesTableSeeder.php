<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert sample companies into the database
        DB::table('companies')->insert([
            [
                'name' => 'شركة الأفق للتجارة',
                'logo' => 'logos/alofok.png',
                'phone_number' => '0123456789',  // رقم الهاتف
                'email' => 'contact@alofok.com', // البريد الإلكتروني
                'address' => 'الرياض، المملكة العربية السعودية', // العنوان
                'additional_info' => 'معلومات إضافية حول الشركة', // معلومات إضافية
                'settings' => json_encode([
                    'currency' => 'SAR',
                    'language' => 'ar',
                    'timezone' => 'Asia/Riyadh'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شركة المدى المحدودة',
                'logo' => 'logos/almada.png',
                'phone_number' => '0987654321',
                'email' => 'info@almada.com',
                'address' => 'جدة، المملكة العربية السعودية',
                'additional_info' => 'تأسست في عام 2000',
                'settings' => json_encode([
                    'currency' => 'USD',
                    'language' => 'en',
                    'timezone' => 'America/New_York'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شركة الريادة العالمية',
                'logo' => 'logos/reyada.png',
                'phone_number' => '01122334455',
                'email' => 'support@reyada.com',
                'address' => 'باريس، فرنسا',
                'additional_info' => 'الشركة تتمتع بخبرة كبيرة في السوق العالمية',
                'settings' => json_encode([
                    'currency' => 'EUR',
                    'language' => 'fr',
                    'timezone' => 'Europe/Paris'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
