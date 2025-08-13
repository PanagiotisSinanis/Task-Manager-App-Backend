<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentStartTimeToTimeEntriesTable extends Migration
{
    public function up()
    {
        Schema::table('time_entries', function (Blueprint $table) {
            $table->timestamp('current_start_time')->nullable()->after('start_time');
        });
    }

    public function down()
    {
        Schema::table('time_entries', function (Blueprint $table) {
            $table->dropColumn('current_start_time');
        });
    }
}
