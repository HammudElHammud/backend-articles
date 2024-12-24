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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('author');
            $table->string('title');
            $table->text('url')->nullable();
            $table->text('urlToImage')->nullable();
            $table->date('publishedAt');
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->foreignIdFor(Source::class)
                ->constrained('sources')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()
                ->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)
                ->constrained('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
