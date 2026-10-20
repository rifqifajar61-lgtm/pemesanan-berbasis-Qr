<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->enum('order_type', ['dine_in', 'takeaway']);
            $table->string('table_number')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'kitchen', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'qris'])->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');
            $table->decimal('cash_amount', 12, 2)->nullable();
            $table->decimal('change_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};