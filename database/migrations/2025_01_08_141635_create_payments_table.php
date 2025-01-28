<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
                $table->id(); // Auto-increment ID
                $table->string('order_id')->unique(); // Razorpay Order ID
                $table->string('payment_id')->nullable(); // Razorpay Payment ID
                $table->string('signature')->nullable(); // Razorpay Signature for verification
                $table->decimal('amount', 10, 2); // Payment amount (e.g., INR)
                $table->string('currency', 10)->default('INR'); // Currency type
                $table->enum('status', ['created', 'success', 'failed'])->default('created'); // Status
                $table->string('customer_name')->nullable(); // Customer name
                $table->string('customer_email')->nullable(); // Customer email
                $table->text('failure_reason')->nullable(); // Reason for failure
                $table->timestamps(); // Created_at and Updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
