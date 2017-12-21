<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoutubeAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_name', 100)->comment('账户名称');
            $table->string('ssk_key_filename', 100)->comment('秘钥文件名称');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('youtube_account');
    }
}
