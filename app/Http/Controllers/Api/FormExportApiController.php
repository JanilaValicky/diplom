<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Exports\FormsCsvExport;
use App\Exports\FormsExcelExport;
use App\Services\FormService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FormExportApiController extends AbstractApiController
{
    private FormService $service;

    public function __construct(FormService $service)
    {
        $this->service = $service;
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = $this->service->getByUserId($request->user->id);

            $timestamp = Carbon::now()->format('Y-m-d_H-i');
            $filename = "forms_{$timestamp}.xlsx";

            return Excel::download(new FormsExcelExport($data), $filename);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $data = $this->service->getByUserId($request->user->id);

            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "forms_{$timestamp}.csv";

            return Excel::download(new FormsCsvExport($data), $filename);
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }
}
