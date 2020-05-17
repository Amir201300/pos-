<?php

namespace Suppliers\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;
use App\User;
use App\Http\Controllers\Manage\BaseController;
use App\Http\Controllers\Manage\EmailsController;

class UserController extends Controller
{
    public function index()
    {
        $user=test_hh();
        return $user;
    }
}