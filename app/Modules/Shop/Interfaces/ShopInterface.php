<?php

namespace Shop\Interfaces;

interface ShopInterface{
    /**
     * @param $request
     * @return mixed
     */
    public function login($request);
}