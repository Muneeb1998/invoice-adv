<?php

namespace App\Controllers;

class Logout extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return  redirect()->to('login');
    }
}
