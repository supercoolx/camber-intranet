<?php
//include_once(dirname(__FILE__)."/Errors.php");
//if(Errors::$inited===false)
//    Errors::init();
class Debug{
        private static $logFilename;
        function __construct($logFilename='events.log'){
            self::$logFilename = $logFilename;
        }
	public static function isDev(){
            $userIp =  isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
            $userIp =  isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $userIp;
  
            $ips = Errors::config('allowed_ips');
           

            if(count($ips)>0){
                foreach($ips as $ip){
                   if(strpos($userIp,$ip)!==false)
                           return true;
                }
            }
            return false;

        }
	public static function isDevServer()
	{
		if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'treng.net') !== false) {
			return true;
		} else {
			return false;
		}
	}
      
        public static function log($str, $filename = false){

	    $bt = debug_backtrace();
            $caller = array_shift($bt);
			
            $logFilename  = ($filename===false) ? self::$logFilename : $filename;

            $logFilename = 'test.html';
            $date  = date("Y-m-d H:i:s");
            $content = "\r\n{$date} {$str} ({$caller})";
            $fp = fopen(dirname(__FILE__)."/./$logFilename", "a"); //LOGS TASK PROCEEDING
            fwrite($fp, $content);
            fclose($fp);
        }
}