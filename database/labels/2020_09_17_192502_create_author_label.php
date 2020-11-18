<?php

use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class CreateAuthorLabel extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Neo4jSchema::label('Author', function (Blueprint $label) {
            $label->unique('first_names' . ' ' . 'last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Neo4jSchema::dropIfExists('Author');
    }
}
