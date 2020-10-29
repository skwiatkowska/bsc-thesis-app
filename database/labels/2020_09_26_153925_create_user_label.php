<?php

use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class CreateUserLabel extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Neo4jSchema::label('User', function (Blueprint $label) {
            $label->unique('pesel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Neo4jSchema::dropIfExists('User');
    }
}
