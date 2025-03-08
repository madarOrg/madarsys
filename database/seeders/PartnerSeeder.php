<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to prevent constraint issues
        Schema::disableForeignKeyConstraints();
        Partner::truncate(); // Clear existing data
        Schema::enableForeignKeyConstraints();

        // Insert partners manually
        Partner::create([
            'name'           => 'شركة توريد 1',
            'type'           => 1, // Supplier
            'contact_person' => 'أحمد محمد',
            'phone'          => '0100000001',
            'email'          => 'supplier@example.com',
            'address'        => 'عنوان المورد 1',
            'tax_number'     => '123456781',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);

        Partner::create([
            'name'           => 'شركة توريد 2',
            'type'           => 1,
            'contact_person' => 'محمد علي',
            'phone'          => '0100000002',
            'email'          => 'supplier2@example.com',
            'address'        => 'عنوان المورد 2',
            'tax_number'     => '123456782',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);

        Partner::create([
            'name'           => 'شركة توريد 3',
            'type'           => 1,
            'contact_person' => 'خالد حسن',
            'phone'          => '0100000003',
            'email'          => 'supplier3@example.com',
            'address'        => 'عنوان المورد 3',
            'tax_number'     => '123456783',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);

        Partner::create([
            'name'           => 'شركة توريد 4',
            'type'           => 1,
            'contact_person' => 'سامي إبراهيم',
            'phone'          => '0100000004',
            'email'          => 'supplier4@example.com',
            'address'        => 'عنوان المورد 4',
            'tax_number'     => '123456784',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);

        Partner::create([
            'name'           => 'عميل 1',
            'type'           => 2, // Customer
            'contact_person' => 'خالد عبد الله',
            'phone'          => '0100000005',
            'email'          => 'customer1@example.com',
            'address'        => 'عنوان العميل 1',
            'tax_number'     => '123456785',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);

        Partner::create([
            'name'           => 'عميل 2',
            'type'           => 2,
            'contact_person' => 'عبد الرحمن يوسف',
            'phone'          => '0100000006',
            'email'          => 'customer2@example.com',
            'address'        => 'عنوان العميل 2',
            'tax_number'     => '123456786',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);

        Partner::create([
            'name'           => 'مندوب 1',
            'type'           => 3, // Representative
            'contact_person' => 'محمود فؤاد',
            'phone'          => '0100000007',
            'email'          => 'representative1@example.com',
            'address'        => 'عنوان المندوب 1',
            'tax_number'     => '123456787',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);

        Partner::create([
            'name'           => 'جهة متبرعة 1',
            'type'           => 4, // Donor
            'contact_person' => 'أيمن جابر',
            'phone'          => '0100000008',
            'email'          => 'donor1@example.com',
            'address'        => 'عنوان الجهة المتبرعة 1',
            'tax_number'     => '123456788',
            'is_active'      => 1,
            'branch_id'      => 2,
            'created_user'   => 1,
            'updated_user'   => 1,
        ]);
    }
}
