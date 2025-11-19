<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->decimal('previous_price', 10, 2)->default(0);
            $table->decimal('new_price', 10, 2);
            $table->decimal('price_change', 10, 2);
            $table->decimal('percentage_change', 8, 2);
            $table->date('change_date');
            $table->timestamps();
            
            $table->index(['product_id', 'change_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_price_history');
    }
};