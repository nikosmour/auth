<?php

namespace Database\Seeders;

use Database\Seeders\Classes\CreatedAtMoreThanSeeder;
use Database\Seeders\Classes\ManyToManySeeder;

class PurchaseCouponSeeder extends ManyToManySeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->make_connection(
            \App\Models\CouponOwner::where('created_at', '>', $this->createdAtMoreThan)->cursor(),
            \App\Models\CouponStaff::all(),
            \App\Models\PurchaseCoupon::class, $this->count);
    }
}
