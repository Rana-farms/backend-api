<?php

namespace Database\Seeders;
use App\Models\Investment;
use Illuminate\Database\Seeder;

class InvestmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Investment::create([
            'name' => 'Agricultural Commodity Trust',
            'trustee' => 'RANA Farms Limited',
            'minimum_unit' => 10,
            'unit_price' => 5,
            'lock_up_period' => 12,
            'insurance_fee' => 300,
            'description' => 'Good Commodity Trust',
            'asset_allocation' => 'Min. 10% - Cash/Max 90% - Commodity',
            'profit_sharing_formula' => '60-40 (Investor Trustee)',
            'risk_profile' => 'High Risk Profile',
        ]);

        Investment::create([
            'name' => 'Agricultural Logistics Trust',
            'trustee' => 'RANA Farms Limited',
            'minimum_unit' => 20,
            'unit_price' => 15,
            'lock_up_period' => 24,
            'insurance_fee' => 500,
            'description' => 'Good Logistics Trust',
            'asset_allocation' => 'Min. 10% - Cash/Max 90% - Commodity',
            'profit_sharing_formula' => '60-40 (Investor Trustee)',
            'risk_profile' => 'High Risk Profile',
        ]);

        Investment::create([
            'name' => 'Agricultural Storage Trust',
            'trustee' => 'RANA Farms Limited',
            'minimum_unit' => 5,
            'unit_price' => 50,
            'lock_up_period' => 6,
            'insurance_fee' => 10,
            'description' => 'Good Storage Trust',
            'asset_allocation' => 'Min. 10% - Cash/Max 90% - Commodity',
            'profit_sharing_formula' => '60-40 (Investor Trustee)',
            'risk_profile' => 'High Risk Profile',
        ]);

    }
}
