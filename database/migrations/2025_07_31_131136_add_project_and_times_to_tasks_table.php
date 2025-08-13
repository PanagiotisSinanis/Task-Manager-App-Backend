<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // ΑΦΑΙΡΕΣΕ ή ΣΧΟΛΙΑΣΕ αυτή τη γραμμή:
            // $table->unsignedBigInteger('project_id')->after('status');
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->timestamp('start_time')->nullable()->after('status');
            $table->timestamp('end_time')->nullable()->after('start_time');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // $table->dropForeign(['project_id']);
            //$table->dropColumn('project_id');

            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
