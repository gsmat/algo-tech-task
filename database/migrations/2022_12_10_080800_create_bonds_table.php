<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('bonds', static function (Blueprint $table) {
            $table->id();
            $table->date('issue_date');
            $table->date('last_circulation_date');
            $table->integer('nominal_price');
            $table->enum('payment_frequency_coupon', [1, 2, 4, 12]);
            $table->enum('calculating_period_interest', [360, 364, 365]);
            $table->tinyInteger('coupon_interest');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('bonds');
    }
};
