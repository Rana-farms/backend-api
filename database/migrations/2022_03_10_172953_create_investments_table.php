<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trustee');
            $table->integer('minimum_unit');
            $table->float('unit_price', 10, 2)->default(0.00);
            $table->integer('lock_up_period');
            $table->float('insurance_fee');
            $table->string('description');
            $table->string('asset_allocation');
            $table->string('profit_sharing_formula');
            $table->string('risk_profile');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments');
    }
}
