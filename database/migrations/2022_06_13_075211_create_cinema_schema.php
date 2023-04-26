<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories
     */
    public function up()
    {
        /**
         * **Movie exploration**
         * As a user I want to see which films can be watched and at what times
         * As a user I want to only see the shows which are not booked out
         */
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('watchable')->default(true);
            $table->timestamps();
        });

        Schema::create('movie_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('showroom_id');
            $table->foreign('showroom_id')->references('id')->on('showrooms')->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('pricing');
            $table->timestamp('slot');
            $table->timestamps();
        });

        /* 
        **Show administration**
        * As a cinema owner I want to run different films at different times
        * As a cinema owner I want to run multiple films at the same time in different showrooms
        */
        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cinema_owner_id');
            $table->foreign('cinema_owner_id')->references('id')->on('cinemas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('location')->nullab;
            $table->timestamps();
        });

        Schema::create('cinema_owners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('showrooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cinema_id');
            $table->foreign('cinema_id')->references('id')->on('cinemas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        /** 
         **Pricing**
         * As a cinema owner I want to get paid differently per show
         * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

         **Seating**
         * As a user I want to book a seat
         * As a user I want to book a vip seat/couple seat/super vip/whatever
         * As a user I want to see which seats are still available
         * As a user I want to know where I'm sitting on my ticket
         * As a cinema owner I dont want to configure the seating for every show
         */
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_slot_id');
            $table->foreign('movie_slot_id')->references('id')->on('movie_slots')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('seat_type_id');
            $table->foreign('seat_type_id')->references('id')->on('seat_types')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('ticket_number');
            $table->$table->timestamps();
        });

        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('percentage');
            $table->unsignedMediumInteger('location');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('movie_seat_type_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_slot_id');
            $table->foreign('movie_slot_id')->references('id')->on('movie_slots')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('seat_type_id');
            $table->foreign('seat_type_id')->references('id')->on('seat_types')->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('number_of_seats');
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
        Schema::dropIfExists('movies');
        Schema::dropIfExists('movie_slots');
        Schema::dropIfExists('cinemas');
        Schema::dropIfExists('cinema_owners');
        Schema::dropIfExists('showrooms');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('users');
        Schema::dropIfExists('seat_types');
        Schema::dropIfExists('movie_seat_type_availabilities');
    }
}
