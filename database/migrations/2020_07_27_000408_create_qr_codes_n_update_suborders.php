<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrCodesNUpdateSuborders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('suborders', function (Blueprint $table) {
            $table->dropColumn(['qr_used','qr_scanned_at','qr_path']);
        });

        Schema::create('qr_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->unsignedBigInteger('suborder_id');
            $table->float('price', 10,2)->nullable();
            $table->float('used', 10,2)->nullable();
            $table->smallInteger('scans')->default(0); // multiple use cases.
            $table->text('path')->nullable();          

            $table->dateTime('scanned_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('suborder_id')->references('id')->on('suborders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_codes');
    }
}
