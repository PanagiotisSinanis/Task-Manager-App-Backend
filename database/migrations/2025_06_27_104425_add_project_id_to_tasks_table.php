<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
     if (!Schema::hasColumn('tasks', 'project_id')) {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
}
}


public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropForeign(['project_id']);
        
    });
}

};
