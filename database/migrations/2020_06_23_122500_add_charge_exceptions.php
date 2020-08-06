<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChargeExceptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_exceptions', function (Blueprint $table) {

            $table->id();

			$table->unsignedBigInteger('charge_id')->nullable();
			$table->foreign('charge_id')->references('id')->on('charges')->onDelete('cascade');

			$table->text('message')->nullable();
			$table->string('scenario')->nullable();

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
        Schema::dropIfExists('charge_exceptions');
    }
}
