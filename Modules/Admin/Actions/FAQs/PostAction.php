<?php


namespace Modules\Admin\Actions\FAQs;

use App\Models\FAQs;
use Illuminate\Http\Request;

class PostAction
{
    public function handle(Request $request)
    {
        $data = $request->all();

        try {
            $page = new FAQs();
            if (isset($data['id']) && $data['id'] > 0) {
                $page = FAQs::find($data['id']);
            }

            if (!isset($data['slug']) || $data['slug'] == '') {
                $data['slug'] = \Str::slug($data['name']);
            }

            $page->fill($data);
            $page->save();

            $output = [
                'code' => 1,
                'message' => 'Update success!',
                'data' => $page
            ];
        } catch (\Throwable $e) {
            $output = [
                'code' => 0,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
        return $output;
    }
}
