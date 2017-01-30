@extends('stubs::class')

@section('body')

    /**
    * Run the migrations.
    *
    * @return void
    */

    public function up()
    {
        Schema::create('{!! $table !!}', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::dropIfExists('{!! $table !!}');
    }

@stop