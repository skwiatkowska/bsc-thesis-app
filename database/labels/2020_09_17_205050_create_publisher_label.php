<?php

use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class CreatePublisherLabel extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Neo4jSchema::label('Publisher', function (Blueprint $label) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Neo4jSchema::dropIfExists('Publisher');
    }
}
