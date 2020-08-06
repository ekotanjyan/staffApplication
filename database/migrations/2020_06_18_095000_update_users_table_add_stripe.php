<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddStripe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_access_token')->nullable()->after('stripe_id');
            $table->string('stripe_refresh_token')->nullable()->after('stripe_access_token');
            $table->string('stripe_publishable_key')->nullable()->after('stripe_refresh_token');
            $table->string('stripe_customer_id')->nullable()->after('stripe_publishable_key');
            $table->string('stripe_card_id')->nullable()->after('stripe_customer_id');
            $table->unsignedDouble('stripe_fee')->nullable()->after('stripe_card_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_access_token', 'stripe_refresh_token', 'stripe_publishable_key', 'stripe_fee']);
        });
    }
}
