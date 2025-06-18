<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Check if foreign key exists before trying to drop it
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND TABLE_NAME = 'sales'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND CONSTRAINT_NAME = 'sales_product_id_foreign'
            ");
            
            if (!empty($foreignKeys)) {
                $table->dropForeign(['product_id']);
            }
            
            // Drop columns if they exist
            if (Schema::hasColumn('sales', 'product_id')) {
                $table->dropColumn(['product_id']);
            }
            
            if (Schema::hasColumn('sales', 'quantity')) {
                $table->dropColumn(['quantity']);
            }
            
            if (Schema::hasColumn('sales', 'price_per_item')) {
                $table->dropColumn(['price_per_item']);
            }
            
            // Rename columns if they exist
            if (Schema::hasColumn('sales', 'sale_date')) {
                $table->renameColumn('sale_date', 'transaction_date');
            }
            
            if (Schema::hasColumn('sales', 'total_price')) {
                $table->renameColumn('total_price', 'total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add back columns
            if (!Schema::hasColumn('sales', 'product_id')) {
                $table->foreignId('product_id')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('sales', 'quantity')) {
                $table->integer('quantity')->nullable()->after('product_id');
            }
            
            if (!Schema::hasColumn('sales', 'price_per_item')) {
                $table->decimal('price_per_item', 15, 2)->nullable()->after('quantity');
            }
            
            // Rename columns back
            if (Schema::hasColumn('sales', 'transaction_date')) {
                $table->renameColumn('transaction_date', 'sale_date');
            }
            
            if (Schema::hasColumn('sales', 'total_amount')) {
                $table->renameColumn('total_amount', 'total_price');
            }
        });
    }
}; 