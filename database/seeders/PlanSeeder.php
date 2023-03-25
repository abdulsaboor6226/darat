<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            ['name' => 'Treadmail','amount'=>1230],
            ['name' => 'Without Treadmail','amount'=>100],
        ];
        foreach($plans as $plan){
            Plan::create($plan);
       }
    }
}
