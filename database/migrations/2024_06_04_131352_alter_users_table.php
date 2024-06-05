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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet', 8, 2)->default(0)->after('email');
            $table->decimal('old_balance', 8, 2)->default(0)->after('wallet');
            $table->integer('cookies')->default(0)->after('old_balance');
            $table->integer('subscribed')->default(0)->after('cookies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wallet');
            $table->dropColumn('old_balance');
            $table->dropColumn('cookies');
            $table->dropColumn('subscribed');
        });
    }
};
