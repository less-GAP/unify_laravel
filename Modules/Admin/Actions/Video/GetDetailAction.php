<?php


namespace Modules\Admin\Actions\Video;


use App\Models\Video;
use App\Providers\AbstractModuleProvider;
use Illuminate\Http\Request;

class GetDetailAction
{
    public function handle(Request $request)
    {
        $video = [];
        $id = $request->route('id');
        if($id > 0){
            $video = Video::where('id',$id)->first();
        }
        return [
            'code' => 1,
            'message' => 'Success!',
            'data' => $video
        ];
    }
}
