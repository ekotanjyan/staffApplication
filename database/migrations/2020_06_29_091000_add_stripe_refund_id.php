<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeRefundId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->string('stripe_refund_id')->nullable();
			$table->unsignedTinyInteger('is_refunded')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('charges', function (Blueprint $table) {
			$table->dropColumn('stripe_refund_id');
			$table->dropColumn('is_refunded');
		});
    }
}