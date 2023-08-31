<?php


namespace Modules\Admin\Actions\MasterData;


class GetOptionsAction
{
    public function handle(Request $request){
        $listKey = $request->route('listKey');
        $masterData = MasterData::find($listKey);
        if($masterData){
            return $masterData->data;
        }
        return [];
    }
}
