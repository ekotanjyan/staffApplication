<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrPathSuborders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suborders', function (Blueprint $table) {
            $table->text('qr_path')->nullable()->after('qr_scanned_at');
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
            $table->dropColumn('qr_path');
        });
    }
}
