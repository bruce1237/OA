<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    protected $folder;
    protected $filePathFullName;
    protected $handler;
    protected $csvHandler;
    protected $csvFileFullName;
    protected $csvFilePathFullName;
    protected $htmlHandler;
    protected $picHandle;
    protected $picPathFullName;

    public function __construct() {
        $this->picPathFullName = storage_path('file/pic_test.png');
        $this->picHandle = fopen($this->picPathFullName, 'r+a');

        $htmlPathFullName = storage_path('file/html_test.html');
        $this->htmlHandler = fopen($htmlPathFullName, 'r+');
        $this->csvFilePathFullName = storage_path('file/csv_test.csv');
        $this->csvHandler = fopen($this->csvFilePathFullName, "r+");

        $this->filePathFullName = storage_path('file/abc.txt'); //abc.txt:aaabbbccc
        $this->folder = storage_path('file/');
        $this->handler = fopen($this->filePathFullName, "r+");
    }

    public function index() {
//        $r = $this->myBaseName($this->filePathFullName);
//        $r = $this->myChgrp($this->filePathFullName,1);
//        $r = $this->myChmod($this->filePathFullName,0777);
//        $r = $this->myChown($this->filePathFullName,1);
//        $r = $this->myClearStatCache(true,$this->filePathFullName);
//        $r = fileowner($this->filePathFullName);
//        $r = filegroup($this->filePathFullName);
//        $r = $this->myDirName($this->filePathFullName);
//        $r = $this->myDiskFreeSpace($this->folder);
//        $r = $this->myDiskTotalSpace($this->folder);
//        $r = $this->myEndOfFile($this->handler);
//        $r = $this->myFileFlush($this->handler);
//        $r = $this->myFileGetChar($this->handler);
//        $r = $this->myFileGetCSV($this->csvHandler);
//        $r = $this->myFileGetS($this->csvHandler);
//        $r = $this->myFileGetStringStrip($this->htmlHandler);
//        $r = $this->myFileExists($this->filePathFullName);
//        $r = $this->myFileGetContents($this->filePathFullName);
//        $r = $this->myFile($this->filePathFullName);
//        $r = $this->myFileATime($this->filePathFullName);
//        $r = date('Y-M-Dd H:s:i',$r);
//        $r = $this->myFileCTime($this->filePathFullName);
//        $r = date('Y-M-Dd H:s:i',$r);
//        $r = $this->myFileMTime($this->filePathFullName);
//        $r = date('Y-M-Dd H:s:i',$r);
//        $r = $this->myFileGroup($this->filePathFullName);
//        $r = $this->myFileInode($this->filePathFullName);
//        $r = $this->myFileOwner($this->filePathFullName);
//        $r = $this->myFilePerms($this->filePathFullName);
//        $r = $this->myFileSize($this->filePathFullName);
//        $r = $this->myFileType($this->filePathFullName);
//        $r = $this->myFileLock($this->handler);
//        $r = fwrite($this->handler,"BBBBB");
//        $r = $this->myFileNameMatch();
//        $r = $this->myFilePassThrough($this->handler);
//        $r = $this->myFilePutCSV($this->handler);
        $r = $this->myFileRead($this->handler);
        $r = $this->myFileRead($this->picHandle);


//        $r = $this->myFileClose($this->handler);
//        dd(copy($this->filePathFullName,'http://search.miluint.com/lottery/img/'));
        return view("admin/file/index", ['data' => $r]);
    }

    protected function myFileRead($picHandle) {
        return fread($picHandle, 10);
    }

    protected function myFilePutCSV($handle) {
        $fields = ['a', 'b', 'c', 'd', 'e', 'f', 'g'];
        return fputcsv($handle, $fields, '*', 'X');
    }

    protected function myFilePassThrough($handle) {
        return fpassthru($handle);
    }

    protected function myFileNameMatch() {
        $pattern = "*JK*jd*";
        $string = "dsafJKlksjdfie";
        return fnmatch($pattern, $string);

    }

    protected function myFileLock($handle) {
        return flock($handle, LOCK_SH);
    }

    protected function myFileType($filePathFullName) {
        return filetype($filePathFullName);
    }

    protected function myFileSize($filePathFullName) {
        return filesize($filePathFullName);
    }

    protected function myFilePerms($filePathFullName) {
        return fileperms($filePathFullName);
    }

    protected function myFileOwner($filePathFullName) {
        return fileowner($filePathFullName);
    }

    protected function myFileInode($filePathFullName) {
        return fileinode($filePathFullName);
    }

    protected function myFileGroup($filePathFullName) {
        return filegroup($filePathFullName);
    }

    protected function myFileMTime($filePathFullName) {
        return filemtime($filePathFullName);
    }

    protected function myFileCTime($filePathFullName) {
        return filectime($filePathFullName);
    }

    protected function myFileATime($filePathFullName) {
        return fileatime($filePathFullName);
    }

    protected function myFile($filePathFullName) {
        return file($filePathFullName);
    }

    protected function myFileGetContents($filePathFullName) {
        return file_get_contents($filePathFullName, false, null, 2, 3);
    }

    protected function myFileExists($fileOrFolderPathName) {
        return file_exists($fileOrFolderPathName);
    }

    /**
     * fgetss() will remove the html tag but show the text between the tags,
     * will remove all the php tag and content between tags.
     * @param $handler
     * @return bool|string
     */
    protected function myFileGetStringStrip($handler) {
        return fgetss($handler);
    }

    protected function myFileGetS($handler) {
        return fgets($handler);
    }

    protected function myFileGetCSV($handler) {
        return fgetcsv($handler);
    }

    protected function myFileGetChar($handler) {
        return fgetc($handler);
    }

    protected function myFileFlush($handler) {
        fwrite($handler, "abc");
        //fwrite: wirte char before the handler point position
//        return fflush($handler);
    }

    protected function myEndOfFile($handler) {
        return feof($handler);
    }

    protected function myFileClose($handler) {
        return fclose($handler);
    }

    protected function myDiskTotalSpace($folder) {
        return disk_total_space($folder);
    }

    protected function myDiskFreeSpace($folder) {
        return disk_free_space($folder);
    }

    protected function myDirName($filePathFullName) {
        return dirname($filePathFullName);
    }

    protected function myClearStatCache(Bool $bool, $filePathFullName) {
        return clearstatcache($bool, $filePathFullName);
    }

    protected function myChown($filePathFullName, $owner) {
        return chown($filePathFullName, $owner);
    }

    protected function myBaseName($filePathFullName) {
        return basename($filePathFullName, 'c.txt');
    }

    protected function myChgrp($filePathFullName, $groupid) {
        return chgrp($filePathFullName, $groupid);
    }

    protected function myChmod($filePathFullName, $mode) {
        return chmod($filePathFullName, $mode);
    }








//    public function myFilePutContents(){
//
//
////        $r = touch(storage_path("ml\\abcd.txt"));
////        $handle = popen(storage_path('ml/for_php_train.csv'),"w");
//        $r = realpath('abc.txt');
//        dd("FF".$r);
//        $data = [
//            "A"=>1,
//            "B"=>2,
//            "C"=>3,
//            "D"=>4,
//            "E"=>5,
//            "F"=>6,
//        ];
//
////        file_put_contents(storage_path('ml/abc.txt'),$data);
//        $handle = fopen(storage_path('ml/abc.txt'),"r");
////        $data = fgets($handle,100);
////        clearstatcache();
//
////      $a = fseek($handle,-6,SEEK_END);
//
////      $c = fpassthru($handle);
////        $c = fstat($handle);
////        $c= ftell($handle);
////        $c = filesize(storage_path('ml/abc.txt'));
//
//        $c = ftruncate($handle,500);
//      dd($c);
//
//
//
//    }


}

