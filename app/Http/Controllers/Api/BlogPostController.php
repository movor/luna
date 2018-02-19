<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

class BlogPostController extends ApiController
{
    public function index()
    {
        return parent::index();
    }

    public function show($id)
    {
        return parent::show($id);
    }
}