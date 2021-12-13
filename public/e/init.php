<?php
 require_once(dirname(__FILE__).'/./Debug.php');
 require_once(dirname(__FILE__).'/./Errors.php');

 Errors::init();
 if(!class_exists("Errors"))
    exit("Errors class not found 1");
 //Errors::setExtraCallback('getErrorUserName');
 Errors::setExtraCallback(function(){
     $res = '<span style="color:green">USER: NA';
      //if(isset(Yii::app()->user)){
        // $res .=  "USERID: ".Yii::app()->user->getId()." USERNAME: ".Yii::app()->user->getName();
     //}
     $res.= '</span>';
     return $res;
 });