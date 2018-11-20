<?php

namespace App\Console\Commands;

use App\Model\Logo;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ESInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:logoImport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import al logos into ES';

    protected $esClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->esClient = ClientBuilder::create()->build();
//        $this->indexEs();


        $logos = Logo::
        leftJoin('logo_cates', 'logos.int_cls', '=', 'logo_cates.id')
            ->leftJoin('logo_flows', 'logos.flow_id', '=', 'logo_flows.id')
            ->leftJoin('logo_goods', 'logos.goods_id', '=', 'logo_goods.id')
            ->leftJoin('logo_length', 'logos.logo_length', '=', 'logo_length.id')
            ->leftJoin('logo_sellers', 'logos.seller_id', '=', 'logo_sellers.id')
            ->leftJoin('logo_type', 'logos.name_type', '=', 'logo_type.id')
          ->select(
                'logos.*',
                'logo_cates.category_name',
                'logo_flows.flow_data',
                'logo_goods.goods_name', 'logo_goods.goods_code',
                'logo_length.name_length',
                'logo_sellers.name', 'logo_sellers.tel', 'logo_sellers.wx', 'logo_sellers.mobile', 'logo_sellers.address', 'logo_sellers.post_code',
                'logo_type.type'
            )
            ->orderBy('id')
            ->chunk(100,function($logos){
                $this->test1($logos);
            });
    }


    protected function indexEs(){


        $params = [
            'index' => config('essearch.es_index'),
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ]
            ]
        ];

        $this->esClient->indices()->create($params);

    }


    private function test1($logos){



        foreach ($logos as $logo) {

            $id = $logo->id;
//                unset($logo->id);


            $params = [
                'index' =>  config('essearch.es_index'),
                'type' =>  config('essearch.es_type'),
                'id' => $id,
                'body' => $logo,
            ];

            $this->esClient->index($params);
        }
        $this->info("------INDEX {$id} SUCCESS--------");

    }
}
