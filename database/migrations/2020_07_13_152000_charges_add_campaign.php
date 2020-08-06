<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChargesAddCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::table('charges', function (Blueprint $table) {
			$table->unsignedBigInteger('campaign_id')->nullable();
			$table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
			$table->unsignedTinyInteger('is_donation')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('charges', 'campaign_id')) {
			Schema::table('charges', function (Blueprint $table) {
				$table->dropForeign('charges_campaign_id_foreign');
				$table->dropColumn('campaign_id');
			});
		}
		if (Schema::hasColumn('charges', 'is_donation')) {
			Schema::table('charges', function (Blueprint $table) {
				$table->dropColumn('is_donation');
			});
		}
	}
}