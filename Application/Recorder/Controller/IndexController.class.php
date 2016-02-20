<?php

namespace Recorder\Controller;
use Think\Controller;

class IndexController extends Controller {

	protected $TABLE_NAME = "location";
	protected $APK_FILE_PATH = "/Files/Apks/";

	function __construct(){
		$this->APK_FILE_PATH = $_SERVER["DOCUMENT_ROOT"].__ROOT__."/Files/Apks/";
	}

    public function index(){
    	$this->show("GPS Location Recorder");
    }

    public function getRecordById($id){
    	$Location = M($this->TABLE_NAME);
    	$result = $Location->find($id);
    	$this->echoResult($result ? true : false, $result);
    }

    public function getRecords($offset = -1, $length = -1){
        $Location = M($this->TABLE_NAME);
        if($length == -1)
            $result = $Location->order("id")->select();
        else
            $result = $Location->order("id")->limit($offset, $length)->select();
        $this->echoResult($result ? true : false, $result);
    }

    public function addRecords(){
        $JsonString = I('post.JsonData','','');
        $DataList = json_decode($JsonString, TRUE);
        if($DataList)
        {
            $Location = M($this->TABLE_NAME);
            $Location->create();
            $Location->addAll($DataList);
            $this->echoResult(true);
        }
        else
            $this->echoResult(false); 
    }

    public function del($id){
    	$Location = M("location");
		$Location->where("id=".$id)->delete();
        $this->echoResult(true);
    }

    public function getApk($package, $channel = "release"){
    	$local_file = $this->APK_FILE_PATH.$package."_".$channel.".apk";
        if (file_exists($local_file) && is_file($local_file)){
        	header('Content-Description: File Transfer');
            header('Cache-control: private');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . basename($local_file) . '"'); 
		    header('Expires: 0');
		    header('Content-Length: '.filesize($local_file));
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    flush();
		    $file = fopen($local_file, "r");
		    while(!feof($file)){
		        // send the current file part to the browser
		        print fread($file, 1024 * 1024);
		        ob_flush();  // flush output		        
		        flush();// flush the content to the browser
		    }
		    fclose($file);
		}
		else{
			echo "null";
		}
	}

	private function echoResult($result, $data = NULL){
		$resultData = array();
		$resultData["Result"] = $result;
		$resultData["Data"] = $data;
		echo json_encode($resultData);
	}


	public function getApkInfo(){
		$path = $this->APK_FILE_PATH;
		$filesnames = scandir($path);
		$apkInfos = array();
		$index = 0;
		$apkFile = new \Vendor\apk\ApkParser();
		foreach ($filesnames as $name) {
			$info = pathinfo($name);
			if($info['extension'] == "apk")
			{				
				$apkFileName = $path.$name;
				$apkFile->open($apkFileName);
				$apkInfo = array();
				$apkInfo["BuildType"] = $apkFile->getBuildType();
				$apkInfo["Package"] = $apkFile->getPackage();
				$apkInfo["VersionName"] = $apkFile->getVersionName();
				$apkInfo["VersionCode"] = $apkFile->getVersionCode();
				$apkInfo["Md5"] = md5_file($apkFileName);
				$apkInfos[$index++] = $apkInfo;
			}
		}
		$this->echoResult(true, $apkInfos);
	}
}