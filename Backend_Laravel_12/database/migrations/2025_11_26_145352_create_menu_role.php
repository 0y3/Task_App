<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $tbl = 'menu_role';
    public function up(): void
    {
        if(!Schema::hasTable($this->tbl)) {
            Schema::create($this->tbl, function (Blueprint $table) {
                $table->foreignId('menu_id')->constrained('menus');
                $table->foreignId('role_id')->constrained('roles');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tbl);
    }
};
