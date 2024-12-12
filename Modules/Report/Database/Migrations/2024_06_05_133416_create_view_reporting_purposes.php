<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Modules\Order\Enums\PaymentEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW order_reporting_purposes AS
            SELECT Row_number() OVER(ORDER BY order_items.id) AS no,
            dropshippers.store_name,
            orders.created_at,
            order_items.order_id as order_no,
            orders.customerName,
            products.sku,
            productTranslations.value as product,
            order_items.quantity,
            orders.grandTotal as COD,
            orders.customerPhone as phone_number,
            orders.customerAddress as address,
            countryTranslations.value as country,
            cityTranslations.value as city,
            orders.customerLocation as location_google_map,
            (case when orders.sent_to_ollops_at IS NOT NULL THEN true else false end) as message_sent,
            orders.attempts_count as no_attempte,
            statusTranslations.value as validation_status,
            sub_statuses.name as validation_aub_atatus,
            remarks.name as validation_remark,
            orders.sent_to_ollops_at as validation_first_attempt,
            orders.validated as validated_At,
            orders.updated_at as last_edit,
            suppliers.name as Submitted_to_Supplier,
            orders.tracking_number as awb,
            orders.pdf_label as awb_link,
            order_statuses_aymakan.description as statuses_aymakan,
            order_statuses_aymakan.updated_at as last_update_aymakan,
            products.supplier_price_cost as COGS,
            (orders.subTotal - (orders.subTotal * 0.14)) as Selling_Price,
            CASE WHEN orders.paymentMethod = 1 THEN "' . PaymentEnum::ONLINE_METHOD . '"
            WHEN orders.paymentMethod = 2 THEN "' . PaymentEnum::CASH_ON_DELIVERY . '"
            ELSE "' . PaymentEnum::WALLET_METHOD . '"
            END AS payment_Method,
            GROUP_CONCAT(notes.content SEPARATOR " - ") AS note
            FROM order_items 
            LEFT JOIN orders ON order_items.order_id=orders.id 
            LEFT JOIN suppliers ON order_items.supplier_id=suppliers.id 
            LEFT JOIN products ON order_items.product_id=products.id 
            LEFT JOIN translations as productTranslations ON order_items.product_id = productTranslations.category_id
            AND productTranslations.category_type like "%Product" AND productTranslations.key = "name"
            AND productTranslations.language_id = 2
            LEFT JOIN dropshippers ON orders.dropshipper_id = dropshippers.id 
            LEFT JOIN translations as statusTranslations ON orders.status_id = statusTranslations.category_id
            AND statusTranslations.category_type like "%Status" AND statusTranslations.key = "name"
            AND statusTranslations.language_id = 2
            LEFT JOIN translations as countryTranslations ON orders.country_id = countryTranslations.category_id
            AND countryTranslations.category_type like "%Country" AND countryTranslations.key = "name"
            AND countryTranslations.language_id = 2
            LEFT JOIN translations as cityTranslations ON orders.customerCity = cityTranslations.category_id
            AND cityTranslations.category_type like "%City" AND cityTranslations.key = "name"
            AND cityTranslations.language_id = 2
            LEFT JOIN sub_statuses ON orders.sub_status_id = sub_statuses.id
            LEFT JOIN remarks ON orders.remark_id = remarks.id
            LEFT JOIN order_statuses_aymakan ON orders.tracking_number = order_statuses_aymakan.tracking
            LEFT JOIN notes ON orders.id = notes.order_id
            GROUP BY order_items.order_id;
        ');

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('id');
            $table->index('dropshipper_id');
            $table->index('country_id');
            $table->index('customerCity');
            $table->index('status_id');
            $table->index('sub_status_id');
            $table->index('remark_id');
            $table->index('tracking_number');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('translations', function (Blueprint $table) {
            $table->index(['category_id', 'category_type', 'key']);
        });

        Schema::table('dropshippers', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('sub_statuses', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('remarks', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('order_statuses_aymakan', function (Blueprint $table) {
            $table->index('tracking');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->index('order_id');
        });
    }

    /** LEFT JOIN translations as productTranslations ON productTranslations.category_id = products.id
     * AND productTranslations.key = "name"
     * ,productTranslations.value as product*/
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS order_reporting_purposes');
        // Drop the indexes if necessary
       Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_order_id_index');
        });

        // Drop other indexes similarly
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_dropshipper_id_index');
            $table->dropIndex('orders_country_id_index');
            $table->dropIndex('orders_customercity_index');
            $table->dropIndex('orders_status_id_index');
            $table->dropIndex('orders_sub_status_id_index');
            $table->dropIndex('orders_remark_id_index');
            $table->dropIndex('orders_tracking_number_index');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex('suppliers_id_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_id_index');
        });

        Schema::table('translations', function (Blueprint $table) {
            $table->dropIndex('translations_category_id_category_type_key_index');
        });

        Schema::table('dropshippers', function (Blueprint $table) {
            $table->dropIndex('dropshippers_id_index');
        });

        Schema::table('sub_statuses', function (Blueprint $table) {
            $table->dropIndex('sub_statuses_id_index');
        });

        Schema::table('remarks', function (Blueprint $table) {
            $table->dropIndex('remarks_id_index');
        });

        Schema::table('order_statuses_aymakan', function (Blueprint $table) {
            $table->dropIndex('order_statuses_aymakan_tracking_index');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex('notes_order_id_index');
        });
    }
};
