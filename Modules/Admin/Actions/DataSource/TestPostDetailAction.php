<?php


namespace Modules\Admin\Actions\DataSource;


use Illuminate\Http\Request;
use Modules\Admin\Services\CrawlerService;

class TestPostDetailAction
{
    public function handle(Request $request)
    {
        $data = $request->input('data');
        $service = app(CrawlerService::class);
        $crawler = $service->initDomCrawler($request->input('url'), $data);
        return  [
            'title'=>$service->filterXPath($crawler, $data['rule_detect_post_title'])->text(),
            'image'=>$service->filterXPath($crawler, $data['rule_detect_post_image'])->text(),
            'excerpt'=>$service->filterXPath($crawler, $data['rule_detect_post_excerpt'])->text(),
            'keywords'=>$service->filterXPath($crawler, $data['rule_detect_post_keywords'])->text(),
            'tags'=>$service->filterXPath($crawler, $data['rule_detect_post_tags'])->text(),
            'content'=>$service->filterXPath($crawler, $data['rule_detect_post_content'])->html(),
        ];
    }
}
