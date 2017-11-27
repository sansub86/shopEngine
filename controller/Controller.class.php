<?php

class Controller
{
    public $view = 'admin';
    public $title;
    public $user;

    function __construct()
    {
        $this->title = Config::get('sitename');
        session_start();
    }

    public function index($data) {
         return ['pages' => Page::getPages()];
    }
}