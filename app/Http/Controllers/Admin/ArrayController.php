<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ArrayController extends Controller
{
    //测试数据
    protected $arr0 = ['a' => 1, 'b' => 1, 'c' => 1];
    protected $arr1 = ['a' => 1, 'b' => 7, 'c' => 3];
    protected $arr2 = ['d' => 1, 'e' => 2, 'f' => 3];
    protected $arr3 = ['a' => 3, 'b' => 2, 'c' => "GHGF"];
    protected $arr4 = array(3, "a" => "green", "b" => "brown", "c" => "blue", "red");
    protected $arr5 = array("a" => "green", "yellow", "red");
    protected $arr6 = array(1, 2, 3, 4, 5);
    protected $arr7 = array(2 => 4, 9 => 3, 7 => 1, 3 => 2, 6 => 3);
    protected $arr8 = array("xa" => "green", "B" => "yellow", "cKey" => "red");
    protected $arr9 = ['a' => 1, 'b' => 2, 'c' => 3];
    protected $arrx = ['a' => 3, 'x' => 2, 'y' => 6];
    protected $arry = [1 => 3, 3 => 20, 20 => 6];

    public function index() {
        dd(Auth::guard('admin')->check());

//        $r = $this->myArrayDiffKey();
//        $r = $this->myArrayDiffUassoc();
//        $r = $this->myArrayDiff();
//        $r = $this->myArrayFillKeys();
//        $r = $this->myArrayFill();
//        $r = $this->myArrayFilter();
//        $r = $this->myArrayFilp();
//        $r = $this->myArrayIntersectAssoc();
//        $r = $this->myArrayIntersect();
//        $r = $this->myArrayIntersectKey();
//        $r = $this->myArrayKeyExists();
//        $r = $this->myArrayKeyFirst();
//        $r = $this->myArrayKeys();
//        $r = $this->myArrayMap();
//        $r = $this->myArrayMapAdv();
//        $r = $this->myArrayMergeRecursive();
//        $r = $this->myArrayMerge();
//        $r = $this->myArrayMultisort();
//        $r = $this->myArrayPad();
//        $r = $this->myArrayPop();
//        $r = $this->myArrayProduct();
//        $r = $this->myArrayPush();
//        $r = $this->myArrayRand();
//        $r = $this->myArrayReduce();
//        $r = $this->myArrayReplaceRecursive();
//        $r = $this->myArrayReplace();
//        $r = $this->myArrayReverse();
//        $r = $this->myArraySearch();
//        $r = $this->myArrayShift();
//        $r = $this->myArraySlice();
//        $r = $this->myArraySplice();
//        $r = $this->myArraySum();
//        $r = $this->myArrayUdiffAssoc();
//        $r = $this->myArrayUdiffUassoc();
//        $r = $this->myArrayUDiff();
//        $r = $this->myArrayUdiffUassoc();
//        $r = $this->myArrayUintersect();
//        $r = $this->myArrayUnique();
//        $r = $this->myArrayUnshift();
//        $r = $this->myArrayValues();
//        $r = $this->myArrayWalkRecursive();
//        $r = $this->myArrayWalk();
//        $r= $this->myARSrot();
//        $r = $this->myASrot();
//        $r = $this->myCompact();
//        $r = $this->myCount();
//        $r = $this->myCurrent();
//        $r = $this->myEnd();
//        echo current($this->arr8);
//        $r = $this->myExtract();
//        $r = $this->myInArray();
//        $r = $this->myKey();
//        $r = $this->myKRSort();
//        dump($this->arr1);
//        $r = $this->myKSort();
//        dump($this->arr8);
//        $r = $this->myList();
//        $r = $this->myNatSort();
//        dump($this->arry);
//        $r = $this->myNext();
//        $r = $this->myPrev();
//        $r = $this->myRange();
//        $r = $this->myReset();
//        $r = $this->myRSrot();
//        $r = $this->myShuffle();
//        dump($this->arr0);
//        $r = $this->myUASort();
//        dump($this->arr1);
//        $r = $this->myUKSort();
//        dump($this->arr1);
//        $r = $this->mySort();
//        dump($this->arr1);
        $r = "this is ARRAY";
        return view("admin/array/index", ['data' => $r]);
    }

    public function mySort(){
        return sort($this->arr1);
    }

    public function myUKSort() {
        return uksort($this->arr1, "self::UASortFunc");
    }

    public function myUASort() {
        return uasort($this->arr1, "self::UASortFunc");
    }

    private function UASortFunc() {
//        return true;//731
        return false;//173
    }

    public function myShuffle() {
        return shuffle($this->arr0);
    }

    public function myRSrot() {
        return rsort($this->arr1);
    }

    public function myReset() {
        next($this->arr1);
        return reset($this->arr1);
    }

    public function myRange() {
        return range("a", "z", 2);
    }

    public function myPrev() {
        next($this->arr1);//move the pointer to next position
        next($this->arr1);//move the pointer to next position
        return prev($this->arr1);
    }

    public function myNext() {
        return next($this->arr1);
    }

    public function myNatSort() {
        return natsort($this->arry);
    }

    public function myNatCaseSort() {
        return natcasesort($this->arry);
    }

    public function myList() {
        list($a, $b, $c) = $this->arr6;
        echo $b;
    }

    public function myKSort() {
        return ksort($this->arr8);
    }

    public function myKRSort() {
        return krsort($this->arr1);
    }

    public function myKey() {
        return key($this->arr1);
    }

    public function myInArray() {
        return in_array(2, $this->arrx);
    }

    public function myExtract() {
        extract($this->arrx);
        echo $x;
        return extract($this->arrx);
    }

    public function myEnd() {
        return end($this->arr8);
    }

    public function myCurrent() {
        return current($this->arr5);
    }

    public function myCount() {
        return count($this->arr8);
    }

    public function myCompact() {
        $name = "bo";
        $sex = "M";
        $age = 23;
        return compact("name", "sex", "age", "address");

    }

    public function myASrot() {
        return asort($this->arr1);
    }

    public function myARSrot() {
        return arsort($this->arr1);
    }

    public function myArrayWalk() {
        return array_walk($this->arr1, "self::myArrayWalkFunc", "BI");
    }

    public function myArrayWalkRecursive() {
        return array_walk_recursive($this->arr8, "self::myArrayWalkFunc", "fff");
    }

    protected function myArrayWalkFunc(&$a, $b, $c) {
        $a = $a . $c;
        $b = $c . $a;
    }

    public function myArrayValues() {
        return array_values($this->arr1);
    }

    public function myArrayUnshift() {
        return array_unshift($this->arr1, "a", "b", "c");
    }

    public function myArrayUnique() {
        return array_unique($this->arr7);
    }

    public function myArrayUintersect() {
        return array_uintersect($this->arr1, $this->arr3, "self::myArrayUDiffFunc");
    }

    public function myArrayUintersectUAssoc() {
        return array_uintersect_uassoc($this->arr1, $this->arr3, "self::myArrayUDiffFunc", "self::myArrayUDiffFunc");
    }


    public function myArrayUintersectAssoc() {
        return array_uintersect_assoc($this->arr1, $this->arr3, "self::myArrayUDiffFunc");
    }

    public function myArrayUDiff() {
        return array_udiff($this->arr1, $this->arrx, "self::myArrayUDiffFunc");
    }

    protected function myArrayUDiffFunc($a, $b) {
//      dump($a);
    }

    public function myArrayUdiffUassoc() {
        return array_udiff_uassoc($this->arr1, $this->arr5, "self::AUU_value_compare_func", "self::AUU_key_compare_func");
    }

    protected function AUU_value_compare_func($a, $b) {
        return true;
    }

    protected function AUU_key_compare_func($a, $b) {
        return false;
    }

    public function myArrayUdiffAssoc() {
        return array_udiff_assoc($this->arr1, $this->arr2, "self::myArrayUdiffAssocFunc");
    }

    /**
     * have no idea why this func is here and what does this func do
     * @param $a
     * @param $b
     * @return bool
     */
    protected function myArrayUdiffAssocFunc($a, $b) {
        return false;
    }

    public function myArraySum() {
        //will ignore the string value
        return array_sum($this->arr4);
    }

    public function myArraySplice() {
        //has changed the this->arr1
        return array_splice($this->arr1, 1, 3, array("x" => 9, "y" => 8));
    }

    public function myArraySlice() {
        return array_slice($this->arr4, -4, -2);
//        return array_slice($this->arr4,1,-2);
    }


    public function myArrayShift() {
        //changed this->arr4
        return array_shift($this->arr4);
    }

    public function myArraySearch() {
        return array_search("blue", $this->arr4);
    }

    public function myArrayReverse() {
        return array_reverse($this->arr6);
    }

    public function myArrayReplace() {
        return array_replace($this->arr1, $this->arr3);
    }

    public function myArrayReplaceRecursive() {
        return array_replace_recursive($this->arr1, $this->arr3);

    }

    public function myArrayReduce() {
        return array_reduce($this->arr9, "self::myArrayReduceFunc", "10");
    }

    protected function myArrayReduceFunc($carry, $item) {
        return $carry + $item;
    }

    public function myArrayRand() {
        return array_rand($this->arr1, 6);
    }

    public function myArrayPush() {
        return array_push($this->arr1, 6, 7, 8, 9, 10);
    }

    public function myArrayProduct() {

        return array_product($this->arr4);
    }

    public function myArrayPop() {
        return array_pop($this->arr1);
    }

    public function myArrayPad() {
        return array_pad($this->arr1, -10, "AAA");
        return array_pad($this->arr1, 10, "AAA");
    }

    public function myArrayMultisort() {
        array_multisort($this->arr4, SORT_ASC, SORT_STRING);
        return $this->arr4;
    }

    public function myArrayMerge() {
        return array_merge($this->arr7); // reset the array index
        return array_merge($this->arr1, $this->arr2, $this->arr3);
    }

    public function myArrayMergeRecursive() {
        return array_merge_recursive($this->arr1, $this->arr2, $this->arr3, $this->arr4, $this->arr5, $this->arr6);
    }

    public function myArrayMapAdv() {
        return array_map("self::arrayMapAdvCallback", $this->arr1, $this->arr5, $this->arr6);
    }

    private function arrayMapAdvCallback($a, $b, $c) {
        //$a is listed value of the first array,
        //$b is listed value of the second array,
        //$c is listed value of the third array......
        //if any of the array has more elements then others, then others will be empty
        return "this is a " . $a . ", this is b " . $b . ", this is c " . $c . "  <br />";

    }

    public function myArrayMap() {
        return array_map("self::arrayMapCallback", $this->arr1);
    }

    private function arrayMapCallback($value) {
        return $value * $value;
    }

    public function myArraykeys() {
        return array_keys($this->arr1, 3);
    }

    public function myArrayKeyFirst() {
        return ($this->arr3);
    }

    public function myArrayKeyExists() {
        return array_key_exists('a', $this->arrx);
    }

    public function myArrayIntersectKey() {
        return array_intersect_key($this->arr1, $this->arr3, $this->arrx);
    }

    public function myArrayIntersect() {
        //不比较键名
        return array_intersect($this->arr1, $this->arr2, $this->arr3);
    }

    public function myArrayIntersectAssoc() {
        return array_intersect_assoc($this->arr1, $this->arr3);
    }

    public function myArrayFilp() {
        return array_flip($this->arr1);
    }

    public function myArrayFilter() {
        return array_filter($this->arr1, "self::get2");
    }

    private function get2($value) {

        return ($value > 2) ? true : false;


    }


    public function myArrayFill() {
        $newArray = array_fill(-2, 4, "ABC");
        /*
         * array:4 [▼
                    -2 => "ABC"
                    0 => "ABC"
                    1 => "ABC"
                    2 => "ABC"
                    ]
         */
        return $newArray;
    }


    public function myArrayFillKeys() {
        return array_fill_keys($this->arr4, "XX");
    }

    public function myArrayDiff() {
        return array_diff($this->arr1, $this->arrx);
    }


    public function myArrayDiffUassoc() {
        return array_diff_uassoc($this->arr4, $this->arr5, "self::myCompareFunc");
    }

    public function myCompareFunc($a, $b) {
        if ($a === $b) {
            return 0;
        }

        return ($a > $b) ? 1 : -1;
    }

    public function myArrayDiffKey() {
        return array_diff_key($this->arr1, $this->arr3);
    }

    /**
     * @return bool
     */
    public function test(): bool {
        //:bool 强制返回bool值
        return "0";
    }

    public function myArrayDiffAssoc() {
        return array_diff_assoc($this->arr1, $this->arr2, $this->arr3);
    }


}
