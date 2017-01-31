@extends('stubs::class')

@section('imports')
    use Illuminate\Notifications\Notifiable;
    use Admin\Extensions\HasAvatar;
@stop

@section('body')
@section('uses')
    use Notifiable, HasAvatar;
@stop

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * Extra permissionables relationships (besides roles)
    *
    * @var array
    */
    protected $permissionables = [

    ];

@stop