<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->nullable();
            $table->foreignId('user_id')->nullable(); // author
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle');
            $table->text('description');
            $table->text('requirements');
            $table->string('price');
            $table->integer('rating');
            $table->string('level');
            $table->timestamps();
        });

        Schema::create('course_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::create('course_user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->foreignId('user_id');
            $table->foreignId('last_completed_task_id');
            $table->integer('progress');
            $table->timestamps();
        });

        Schema::create('course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->foreignId('user_id');
            $table->text('review');
            $table->integer('rating');
            $table->timestamps();
        });

        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->string('title');
            $table->text('description');
            $table->integer('order');
            $table->string('introduction_video_url');
            $table->timestamps();
        });

        Schema::create('course_lesson_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->foreignId('course_lesson_id');
            $table->string('title');
            $table->text('description');
            $table->integer('order');
            $table->text('objectives_explanation');
            $table->text('objectives');
            $table->text('hint');
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
        Schema::dropIfExists('courses');
    }
}
