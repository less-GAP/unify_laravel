<?php


namespace Modules\Admin\Actions\Patient;

use Illuminate\Http\Request;

class PostUploadImageAction
{
    public function handle(Request $request)
    {
        $fileInfo = [];
        $file = $request->file('image');
        if (!empty($file)) {
            $size = $file->getSize();
            $mimeType = $file->getClientMimeType();
            $extension = $file->guessClientExtension();
            $fileName = 'patients/' . uniqid() . '-' . \Str::slug(basename(str_replace($extension, '', $file->getClientOriginalName()))) . '.' . $extension;
            $file->storeAs(
                '', $fileName, ['disk' => 'upload']
            );

            $fileInfo = [
                'name' => $fileName,
                'extension' => $extension,
                'mimeType' => $mimeType,
                'size' => $size,
                'url' => url('upload/' . $fileName, '', env('APP_ENV') == 'local' ? false : true)
            ];
        }
        return $fileInfo;
    }
}
