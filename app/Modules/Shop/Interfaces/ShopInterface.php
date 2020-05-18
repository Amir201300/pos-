<?php

namespace Shop\Interfaces;

interface ShopInterface{
    /**
     * @param $request
     * @return mixed
     */
    public function login($request);

    /**
     * @param $request
     * @return mixed
     */
    public function getInfo($request);

    /**
     * @param $request
     * @return mixed
     */
    public function edit_inf($request);
}