<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'name_uz')) {
                $table->string('name_uz')->nullable();
            }
            if (! Schema::hasColumn('products', 'description_uz')) {
                $table->text('description_uz')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'name_uz')) {
                $table->dropColumn('name_uz');
            }
            if (Schema::hasColumn('products', 'description_uz')) {
                $table->dropColumn('description_uz');
            }
        });
    }
};