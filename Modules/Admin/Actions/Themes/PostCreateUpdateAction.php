<?php


namespace Modules\Admin\Actions\Themes;


use App\Models\Theme;
use Illuminate\Http\Request;

class PostCreateUpdateAction
{
    public function handle(Request $request)
    {
        $file = $request->file('file');
        $fileName = $request->input('name').'.zip';
        $file->storeAs('', $fileName, 'themes');
        if(is_dir(\Storage::disk('themes')->path($request->input('name')))){
            shell_exec("rm -rf " . \Storage::disk('themes')->path($request->input('name')));
        }
        $zip = new \ZipArchive;
        if ($zip->open(\Storage::disk('themes')->path($fileName)) === TRUE) {
            $zip->extractTo(\Storage::disk('themes')->path('/'.$request->input('name').'/'));
            $zip->close();
        }
        $result = Theme::updateOrCreate(['id' => $request->input('id')], $request->all());
        return [
            'result' => $result,
            'message' => $request->input('id') ? 'Update Successfully!' : 'Create Successfully!'
        ];
    }
}
