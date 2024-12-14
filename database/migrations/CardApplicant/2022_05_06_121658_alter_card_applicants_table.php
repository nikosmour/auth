<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_applicants', function (Blueprint $table) {
            $table->dropColumn([
                'expiration_date',
                'permanent_address',
                'permanent_address_phone',
                'temporary_address',
                'temporary_address_phone',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_applicants', function (Blueprint $table) {
            //
        });
    }
};
