<?php
namespace Recorder\Controller;
use Think\Controller;
class IndexController extends Controller {

	protected $TABLE_NAME = "location";

    public function index(){
    	$this->show("GPS Location Recorder");
    }

    public function getRecordById($id){
    	$Location = M($this->TABLE_NAME);
    	$result = $Location->find($id);        
        echo $result ? json_encode($result) : "Failed";
    }

    public function getRecords($offset = -1, $length = -1){
        $Location = M($this->TABLE_NAME);
        if($length == -1)
            $result = $Location->order("id")->select();
        else
            $result = $Location->order("id")->limit($offset, $length)->select();
        echo $result ? json_encode($result) : "Failed";
    }

    public function addRecords(){
        echo "Ok";
    }

    public function add(){    	
        $JsonData = I('post.JsonData','','');
        $DataList = json_decode($JsonData, TRUE);
        if($DataList)
        {
            $Location = M($this->TABLE_NAME);
            $Location->create();
            $Location->addAll($DataList);
            echo "Ok";
            return;
        }
        
        echo "Failed";		
    }

    public function del($id){
    	$Location = M("location");
		$Location->where("id=".$id)->delete();
        echo "Ok";
    }
}