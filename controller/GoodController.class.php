<?php

class GoodController extends Controller
{
    public $view = 'good';

    public function index($data)
    {
        $good = Good::getGood($data['id']);
        return ['good' => $good[0]];
    }
}