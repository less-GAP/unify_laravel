<?php


class LessgapApi
{
    public function getPosts(array $tags,$limit =100)
    {
        return json_decode(file_get_contents(config('api_url') . '/posts/all?limit='.$limit.'&filter[tags]=' . implode(',',$tags)), true);
    }
    public function getPostDetail(string $slug)
    {
        return json_decode(file_get_contents(config('api_url') . '/posts/detail/'.$slug), true);
    }
}
