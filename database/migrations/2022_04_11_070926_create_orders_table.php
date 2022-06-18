<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('weight');
            $table->string('weight_received')->nullable();
            $table->string('weight_loss')->nullable();
            $table->string('aggregated')->nullable();
            $table->enum('order_status', ['Pending', 'Approved'])->default('Pending');
            $table->enum('aggregation_status', ['Pending', 'Initiated', 'Completed'])->default('Pending');
            $table->enum('negotiation_status', ['Pending', 'Initiated', 'Completed'])->default('Pending');
            $table->enum('payment_status', ['Pending', 'Initiated', 'Completed'])->default('Pending');
            $table->enum('delivery_status', ['Pending', 'Initiated', 'Completed'])->default('Pending');
            $table->enum('produce_loading', ['Pending', 'Initiated', 'Completed'])->default('Pending');
            $table->string('code')->unique();
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
        Schema::dropIfExists('orders');
    }
}
