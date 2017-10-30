<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->default(0)->comment('上传信息的用户id');
            $table->string('filename', 100)->comment('要获取的文件名称');
            $table->string('csv_path', 100)->comment('上传的csv文件路径');
            $table->string('csv_filename', 100)->comment('上传的csv文件名称');
            $table->string('vid', 64)->comment('视频id');
            $table->tinyInteger('status')->default(0)->comment('当前文件处理的状态');
            $table->timestamps();
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_record');
    }
}
