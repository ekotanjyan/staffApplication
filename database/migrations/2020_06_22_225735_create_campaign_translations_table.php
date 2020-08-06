<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('campaign_translations', function(Blueprint $table)
  		{
  			$table->bigIncrements('id');
  			$table->unsignedBigInteger('campaign_id');
  			$table->string('locale')->index();
  			$table->string('name')->nullable();
  			$table->text('description')->nullable();
  			$table->string('slug')->nullable();
  			$table->timestamps();
  			$table->unique(['campaign_id','locale','slug']);
        $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
  		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_translations');
    }
}
