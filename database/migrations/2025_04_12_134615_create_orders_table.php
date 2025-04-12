<?php

use App\Enums\StatusOrderEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('costumer_name');
            $table->string('destination_name');
            $table->date('departure_date');
            $table->date('return_date');
            $table->enum('status', [
                StatusOrderEnum::REQUESTED->value,
                StatusOrderEnum::APPROVED->value,
                StatusOrderEnum::CANCELED->value,
            ])->default(StatusOrderEnum::REQUESTED->value);
            $table->timestamps();
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
