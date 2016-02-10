<?php
namespace Recorder\Controller;
use Think\Controller;
class IndexController extends Controller {

	protected $TABLE_NAME = "location";

    public function index(){
    	$this->show("GPS Location Recorder");
    }

    public function display($id){
    	$Location = M($this->TABLE_NAME);
    	$result = $Location->find($id);
    	if($result){
    		dump($result);
    	}
    }

    public function add(){    	
        $JsonData = I('post.JsonData','');
        echo $JsonData;
        $Data = json_decode($JsonData, TRUE);
        dump($Data);
		//$Location = M($this->TABLE_NAME);
		//$Location->create();
		//$Location->add();
		//$this->show("OK");
    }

    public function del($id){
    	$Location = M("location");
		$Location->where("id=".$id)->delete();
    }

}