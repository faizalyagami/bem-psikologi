<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('aspirations', function (Blueprint $table) {
            $table->string('ticket_code')->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('aspirations', function (Blueprint $table) {
            $table->dropColumn('ticket_code');
        });
    }
};
