<?php

use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * author_name для отображения; user_id nullable при удалении автора.
     */
    public function up(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->string('author_name')->default('')->after('user_id');
        });

        Guide::query()->with('user')->each(function (Guide $guide) {
            $user = $guide->user;
            $name = 'Admin';
            if ($user !== null) {
                $roleVal = $user->getRawOriginal('role') ?? $user->role;
                $roleVal = $roleVal instanceof \App\Enums\UserRole ? $roleVal->value : (string) $roleVal;
                $name = $roleVal === 'admin' ? 'Admin' : (string) $user->name;
            }
            $guide->updateQuietly(['author_name' => $name]);
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn('author_name');
        });
    }
};
