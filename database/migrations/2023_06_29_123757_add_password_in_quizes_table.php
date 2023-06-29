<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizes', function (Blueprint $table) {
            $table->string('password', 20)->nullable()->after('key');
            $table->boolean('status')->default(false)->comment('0 - Not Started, 1 - Started')->after('password');
            $table->boolean('visibility')->default(false)->comment('0 - Public, 1 - Private')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizes', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('status');
            $table->dropColumn('visibility');
        });
    }
};
