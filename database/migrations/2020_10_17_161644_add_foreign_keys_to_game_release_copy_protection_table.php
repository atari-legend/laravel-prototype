<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGameReleaseCopyProtectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_release_copy_protection', function (Blueprint $table) {
            $table->foreign('release_id', 'game_release_copy_protection_ibfk_1')->references('id')->on('game_release')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('copy_protection_id', 'game_release_copy_protection_ibfk_2')->references('id')->on('copy_protection')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_release_copy_protection', function (Blueprint $table) {
            $table->dropForeign('game_release_copy_protection_ibfk_1');
            $table->dropForeign('game_release_copy_protection_ibfk_2');
        });
    }
}
