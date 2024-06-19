<?php

namespace App\Repositories;

use App\Models\Form;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Support\Collection;

class FormRepository implements BaseRepositoryInterface
{
    private const VALID_SEARCH_PARAMS = ['name', 'slug', 'start_date', 'end_date', 'status'];

    public function getAll(): Collection
    {
        return Form::all();
    }

    public function create(array $details)
    {
        return Form::create($details);
    }

    public function get($id)
    {
        return Form::findOrFail($id);
    }

    public function update($id, array $details)
    {
        $form = Form::findOrFail($id);
        $form->update($details);

        return $form;
    }

    public function delete($id)
    {
        return Form::destroy($id);
    }

    public function exists($id): bool
    {
        return !is_null(Form::find($id));
    }

    public function getByUserId(int $user_id): Collection
    {
        return Form::where('user_id', $user_id)->get();
    }

    public function getByParamsPaginated(int $user_id, array $params)
    {
        $formsQuery = Form::where('user_id', $user_id);
        if ($params['order'][0]) {
            $index = $params['order'][0]['column'];
            $column = $params['columns'][$index]['data'];
            $formsQuery->orderBy($column, $params['order'][0]['dir']);
        }

        if ($params['search']) {
            $formsQuery->where(function ($query) use ($params) {
                foreach ($params['columns'] as $column) {
                    if (in_array($column['data'], self::VALID_SEARCH_PARAMS)) {
                        $query->orWhere($column['data'], 'like', '%' . $params['search'] . '%');
                    }
                }
            });

        }
        $pageNumber = $params['start'] / $params['length'] + 1;

        return $formsQuery->paginate(perPage: $params['length'], page: $pageNumber);
    }

    public function getByIdAndUserId(int $id, int $user_id)
    {
        return Form::where('id', $id)->where('user_id', $user_id)->first();
    }
}
