<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('name');
            $table->text('vatid');
            $table->text('description');
            $table->unsignedBigInteger('category_id');
            $table->string('address',100);
            $table->string('address2',100)->nullable();
            $table->string('city',100);
            $table->string('province',100)->nullable();
            $table->integer('zip')->nullable();
            $table->string('country',100);
            $table->enum('status', [0,1,2,3])->default(0); //0 = inactive, 1 = verified, 2 = activated, 3 = banned
            $table->string('telephone',100)->nullable();
            $table->string('website',100)->nullable();
            $table->text('twitter')->nullable();
            $table->text('facebook')->nullable();
            $table->text('instagram')->nullable();
            $table->enum('accept_terms', [0,1])->default(0);
            $table->timestamp('accept_terms_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('business_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
