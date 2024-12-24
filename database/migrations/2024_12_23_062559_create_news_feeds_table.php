<?php

use App\Models\Category;
use App\Models\Source;
use App\Models\User;
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
        Schema::create('news_feeds', function (Blueprint $table) {
            $table->id();
            $table->string('author')->nullable();
            $table->foreignIdFor(User::class)
                ->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->nullable()
                ->constrained('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Source::class)->nullable()
                ->constrained('sources')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_feeds');
    }
};
