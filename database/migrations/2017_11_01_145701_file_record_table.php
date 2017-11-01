<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_record', function (Blueprint $table) {
            $table->string('xml_name', '100')->nullable()->comment('youtube视频xml文件名称')->after('vid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_record', function (Blueprint $table) {
            //
        });
    }
}
