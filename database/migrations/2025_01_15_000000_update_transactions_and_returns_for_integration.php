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
        // Update transactions table
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'customer_name')) {
                $table->string('customer_name')->after('branch_id');
            }
            if (!Schema::hasColumn('transactions', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('customer_name');
            }
            if (!Schema::hasColumn('transactions', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('transactions', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0)->after('discount_amount');
            }
            if (!Schema::hasColumn('transactions', 'invoice_number')) {
                $table->string('invoice_number')->unique()->after('id');
            }
            if (!Schema::hasColumn('transactions', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        // Update transaction_items table
        Schema::table('transaction_items', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_items', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('transaction_items', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('discount_percentage');
            }
        });

        // Update return_transactions table (if exists, otherwise create)
        if (!Schema::hasTable('return_transactions')) {
            Schema::create('return_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('branch_id')->constrained()->onDelete('restrict');
                $table->foreignId('employee_id')->constrained('users')->onDelete('restrict');
                $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('restrict');
                $table->text('reason');
                $table->decimal('total', 12, 2)->default(0);
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('return_transactions', function (Blueprint $table) {
                if (!Schema::hasColumn('return_transactions', 'approved_by')) {
                    $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
                }
                if (!Schema::hasColumn('return_transactions', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }
                if (!Schema::hasColumn('return_transactions', 'reason')) {
                    $table->text('reason')->after('transaction_id');
                }
            });
        }

        // Update return_items table (if exists, otherwise create)
        if (!Schema::hasTable('return_items')) {
            Schema::create('return_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('return_transaction_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('restrict');
                $table->integer('quantity');
                $table->decimal('price', 12, 2);
                $table->decimal('subtotal', 12, 2);
                $table->text('reason')->nullable();
                $table->string('condition')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove added columns from transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'subtotal', 'discount_amount', 'total_amount', 'invoice_number', 'notes']);
        });

        // Remove added columns from transaction_items
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'discount_amount']);
        });

        // Remove added columns from return_transactions
        if (Schema::hasTable('return_transactions')) {
            Schema::table('return_transactions', function (Blueprint $table) {
                $table->dropColumn(['approved_by', 'approved_at', 'reason']);
            });
        }
    }
};
