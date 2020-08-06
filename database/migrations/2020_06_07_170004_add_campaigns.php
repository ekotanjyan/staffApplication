<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AddCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->integer('master')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');
            $table->date('start_date')->default(Carbon::now());
            $table->date('end_date')->nullable();
            $table->integer('target');
            $table->text('tnc')->nullable();
            $table->enum('status', ['1','4','2','0','5','3'])->default(0); //0=not_approved, 1=active 3= cancelled 2=paused 4=finished 5=successful
            $table->text('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
