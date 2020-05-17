<?php

namespace Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Shop extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $guard = 'Shop';

}