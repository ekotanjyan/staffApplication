<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableCampaignIdToSuborders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suborders', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suborders', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_id')->change();
        });
    }
}
