<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->constrained()->cascadeOnDelete();
            $table->foreignId("user_id")->nullable()->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->string("slug");
            $table->string("image");
            $table->string("code");
            $table->integer("qty");
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2)->nullable();
            $table->text("description");
            $table->boolean("hot_deal")->default(false);
            $table->boolean("special_offer")->default(false);
            $table->boolean("featured")->default(false);
            $table->enum("status", ["active","inactive"]);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
