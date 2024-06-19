<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\BrandRepository;
use App\Repositories\FormRepository;

class SubdomainWebController extends Controller
{
    private BrandRepository $repository;

    private FormRepository $form_repository;

    public function __construct(BrandRepository $repository, FormRepository $form_repository)
    {
        $this->repository = $repository;
        $this->form_repository = $form_repository;
    }

    public function showForm($brand, $form_id)
    {
        $brand_data = $this->repository->getByName($brand);
        if (!$brand_data) {
            abort(404, 'Brand not found');
        }

        $branded_user_id = $brand_data->user_id;
        $form = $this->form_repository->getByIdAndUserId($form_id, $branded_user_id);

        if (!$form) {
            abort(404, 'Form doesnt belong to user');
        }

        $form_name = $form->name;

        return view('dashboard.forms.branded-form', ['brand' => $brand, 'form_name' => $form_name]);
    }
}
