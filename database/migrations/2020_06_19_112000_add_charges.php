<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {

            $table->id();

			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('seller_id');
			$table->unsignedBigInteger('order_id')->nullable();
			$table->unsignedBigInteger('currency_id');

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
			$table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');

			$table->string('status');
			$table->unsignedTinyInteger('is_paid');

			$table->string('stripe_charge_id')->nullable();

			$table->string('stripe_customer_id')->nullable();
			$table->string('stripe_id')->nullable();

			$table->integer('amount');
			$table->integer('amount_fee');
			$table->integer('amount_refund')->nullable();


			$table->timestamp('charged_at', 0)->nullable();
			$table->timestamp('refunded_at', 0)->nullable();
			$table->timestamp('failed_at', 0)->nullable();

			$table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
}
