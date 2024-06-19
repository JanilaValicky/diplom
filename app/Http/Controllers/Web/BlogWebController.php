<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class BlogWebController extends Controller
{
    public function index()
    {
        return view('blog.index');
    }

    public function show()
    {
        return view('blog.show');
    }
}
