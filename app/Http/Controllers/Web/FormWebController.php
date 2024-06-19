<?php

namespace App\Http\Controllers\Web;

use App\Enums\FormStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\FormService;
use Illuminate\Support\Facades\Auth;

class FormWebController extends Controller
{
    private FormService $service;

    public function __construct(FormService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('dashboard.forms.index');
    }

    public function create()
    {
        return view('dashboard.forms.create')->with(['statuses' => FormStatusEnum::getAllValues()]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $form_id)
    {
        $form = $this->service->get(Auth::user()->id, $form_id);

        return view('dashboard.forms.edit')->with(
            [
                'statuses' => FormStatusEnum::getAllValues(),
                'form' => $form->resolve(),
            ]);
    }
}
