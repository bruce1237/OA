<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Phpml\Classification\Ensemble\RandomForest;
use Phpml\Classification\MLPClassifier;
use Phpml\Dataset\CsvDataset;


class MLController extends Controller
{
    //

    public function __construct() {
//        $this->titanic();
        $this->neuralNetWork();


    }

    public function index(){
        return view("admin/ml/index");
    }

    public function neuralNetWork(){
        $mlp = new MLPClassifier(4,[2],['HR','LR','NS']);
        //4 modes in input layer, 2 nodes in first hidden layer and 3 possible labels


    }

    private function getSample(){

    }




    public function titanic(){

        $a = file(storage_path('ml\\for_php_test.csv'));
//        dd($a);

        $dataSet = new CsvDataset(storage_path('ml\\for_php_train.csv'),5,true);
        $testSet = new CsvDataset(storage_path('ml\\for_php_test.csv'),4, true);
//        $r = $testSet->getColumnNames();
        $sample = $dataSet->getSamples();
        $label = $dataSet->getTargets();

        $randomForest = new RandomForest();
        $randomForest->train($sample,$label);

        $result = $randomForest->predict($testSet->getSamples());


        $csv =[];
         $csv[0]['PassengerId'] = 'PassengerId';
         $csv[0]['Survived'] = 'Survived';
        foreach ($result as $key=>$value) {
            $csv[$key+1]['PassengerId'] = $key+892;
            $csv[$key+1]['Survived'] = $value;
         }

//         dd($csv);
        $file = fopen('write.csv','a+b');
        $data = $csv;
        foreach ($data as $value) {
            fputcsv($file,$value);
        }
        fclose($file);





    }


}
