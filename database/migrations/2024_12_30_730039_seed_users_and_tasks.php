<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class SeedUsersAndTasks extends Migration
{
    public function up()
    {
        Artisan::call('db:seed', ['--class' => 'UserTaskSeeder']);
    }

    public function down()
    {

    }
}