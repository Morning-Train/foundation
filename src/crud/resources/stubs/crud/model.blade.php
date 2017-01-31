@extends('stubs::class')

@section('body')

    @if(isset($table))
        /**
        * Table name
        *
        * @var string
        */
        protected $table = '{!! $table !!}';
    @endif

    /**
    * Dates
    *
    * @var array
    */
    protected $dates = [
    'created_at',
    'updated_at'
    ];

@stop