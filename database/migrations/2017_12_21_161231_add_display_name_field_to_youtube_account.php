<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayNameFieldToYoutubeAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('youtube_account', function (Blueprint $table) {
            $table->string('display_name', 50)->default('')->nullable()->after('id')->comment('显示名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('youtube_account', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });
    }
}
