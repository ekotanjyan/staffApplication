<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('code')->nullable();
			$table->float('value')->nullable();
			$table->timestamps();
            $table->softDeletes();
        });
		$data = [
			['id' => 1, 'name' => 'EUR', 'value' => null],
		];
		DB::table('currencies')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
