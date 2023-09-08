<?php


namespace App\Builder\Filters;


use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchRelationShip implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $fields = explode(',', $property);

        foreach ($fields as $field) {
            $filter = explode('.', $field);
            if (count($filter) == 2) {
                $query->whereHas($filter[0], function ($subQuery) use ($filter,$value,$field) {
                    $subQuery->where($field, 'like', '%'.$value.'%');
                });
            }
        }

        return $query;
    }
}
