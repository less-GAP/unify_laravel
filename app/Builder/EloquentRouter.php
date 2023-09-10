<?php


namespace App\Builder;


use App\Models\EmailTemplate;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\QueryBuilder\QueryBuilder;

class EloquentRouter
{
    private $model;
    private $config;

    public function __construct(private $prefix)
    {
    }

    public static function prefix(string $prefix)
    {
        $handler = new static($prefix);
        return $handler;
    }

    public function routes($routes = null)
    {
        Route::prefix($this->prefix)->group($routes);
        return $this;
    }

    public function handle($model, array $config)
    {
        $this->model = new $model;
        $this->config = $config;
        $prefix = $this->prefix;

        Route::prefix($prefix)->group(function () use ($config) {
            $apiList = ['list', 'all', 'detail', 'create', 'update', 'relations', 'delete'];
            if (!empty($config['apiList'])) {
                $apiList = $config['apiList'];
            }
            if (in_array('all', $apiList)) {
                Route::get('all', function (Request $request) {
                    return $this->getAll($request);
                });
            }
            if (in_array('list', $apiList)) {
                Route::get('list', function (Request $request) {
                    return $this->getList($request);
                });
            }
            if (in_array('create', $apiList)) {
                Route::post('', function (Request $request) {
                    return $this->updateOrCreate($request);
                });
            }
            if (in_array('update', $apiList)) {
                Route::put('{id}', function (Request $request) {
                    return $this->update($request);
                });
            }
            if (in_array('detail', $apiList)) {
                Route::get('{id}', function (Request $request) {
                    return $this->getDetail($request);
                });
            }
            if (in_array('relations', $apiList)) {
                Route::get('/{id}/{relation}', function (Request $request) {
                    return $this->getRelation($request);
                });
                Route::post('/{id}/{relation}/{relationId}', function (Request $request) {
                    return $this->attachRelation($request);
                });
                Route::delete('/{id}/{relation}/{relationId}', function (Request $request) {
                    return $this->detachRelation($request);
                });
            }

            if (in_array('delete', $apiList)) {
                Route::delete('{id}', function (Request $request) {
                    return $this->delete($request);
                });
            }
        });
        return $this;
    }

    public function getList(Request $request)
    {
        return QueryBuilder::for($this->model)
            ->defaultSort($this->config['defaultSort'] ?? '-id')
            ->allowedIncludes($this->config['allowedIncludes'] ?? [])
            ->allowedFilters($this->config['allowedFilters'] ?? [])
            ->allowedSorts($this->config['allowedSorts'] ?? 'id')
            ->paginate($request->input('perPage', 15))
            ->appends($request->query());
    }

    public function getAll(Request $request)
    {
        return QueryBuilder::for($this->model)
            ->defaultSort($this->config['defaultSort'] ?? '-id')
            ->allowedIncludes($this->config['allowedIncludes'] ?? [])
            ->allowedFilters($this->config['allowedFilters'] ?? [])
            ->allowedSorts($this->config['allowedSorts'] ?? 'id')
            ->groupBy(\DB::raw($this->config['groupBy'] ?? 'id'))
            ->limit($request->input('limit', $this->config['limit'] ?? 100))
            ->get();
    }

    public function getDetail(Request $request)
    {
        $model = new $this->model;
        $query = $this->model::query();
        if ($id = $request->route('id')) {
            $query->where($model->getKeyName(), $id);
        }
        return $query->with($this->config['allowedIncludes'] ?? [])->first();
    }

    public function getRelation(Request $request)
    {
        $model = new $this->model;
        $query = $this->model::query();
        if ($id = $request->route('id')) {
            $query->where($model->getKeyName(), $id);
        }
        $model = $query->first();

        $relation = $request->route('relation');
        if (!$model || !$relation) {
            return [];
        }
        return $model->$relation->toArray();
    }

    public function attachRelation(Request $request)
    {
        $model = new $this->model;
        $query = $this->model::query();
        if ($id = $request->route('id')) {
            $query->where($model->getKeyName(), $id);
        }
        $model = $query->first();

        $relation = $request->route('relation');
        if (!$model || !$relation) {
            return [];
        }
        $id = $request->route('relationId');
        $model->$relation()->attach([$id]);
        return [
            'message' => 'Success!'
        ];
    }

    public function detachRelation(Request $request)
    {
        $model = new $this->model;
        $query = $this->model::query();
        if ($id = $request->route('id')) {
            $query->where($model->getKeyName(), $id);
        }
        $model = $query->first();

        $relation = $request->route('relation');
        if (!$model || !$relation) {
            return [];
        }
        $id = $request->route('relationId');
        $model->$relation()->detach([$id]);
        return [
            'message' => 'Success!'
        ];

    }

    public function delete(Request $request)
    {
        $query = $this->model::query();
        return [
            'result' => $query->find($request->route('id'))->delete(),
            'message' => 'Delete Successfully!'
        ];
    }

    public function updateOrCreate(Request $request)
    {
        $model = new $this->model;
        $data = $request->all();
        $result = $this->model::updateOrCreate([$model->getKeyName() => $request->input($model->getKeyName())], $data);

        return [
            'result' => $result,
            'message' => $request->input($model->getKeyName()) ? 'Update Successfully!' : 'Create Successfully!'
        ];
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $result = $this->model::find($request->route('id'))->update($data);
        return [
            'result' => $result,
            'message' => 'Update Successfully!'
        ];
    }

    public function addRoute($callback)
    {
        Route::prefix($this->prefix)->group($callback);
    }
}
