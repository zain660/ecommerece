<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VariantSkuPrefixColumnAddProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $table->string('variant_sku_prefix')->nullable()->after('product_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('variant_sku_prefix');
            });
        }
    }
}
