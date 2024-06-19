<?php

namespace App\Http\Controllers;

use App\HTTPClient;

class BlogWebController extends Controller
{
    protected $HTTPClient;

    public function __construct(HTTPClient $HTTPClient)
    {
        $this->HTTPClient = $HTTPClient;
    }

    public function index()
    {
        $response = $this->HTTPClient->sendRequest('GET', '/posts');

        return view('brand.index', ['response' => $response]);
    }

    public function show()
    {
        return view('blog.show');
    }
}
