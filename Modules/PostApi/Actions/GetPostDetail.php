<?php


namespace Modules\PostApi\Actions;


use App\Models\Post;
use Illuminate\Http\Request;

class GetPostDetail
{
    public function handle(Request $request)
    {
        return Post::where('slug', $request->route('slug'))->first();
    }
}
