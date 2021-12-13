<?php
//TODO create Debug class
//TODO i had bug when i had 1 Gb log in 1 min
//prevent this!!!
/* Usage
 *
 *  require_once("/e/Errors.php");
 *  Errors::init();
 *  Errors::init($config);
 *  */
//TODO log part of IP (last 2 digits)
if (is_file(dirname(__FILE__) . '/' . 'Site.php'))
	require_once(dirname(__FILE__) . '/' . 'Site.php');
if (is_file(dirname(__FILE__) . '/' . 'Bench.php'))
	require_once(dirname(__FILE__) . '/' . 'Bench.php');
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'treng.net') !== false) {
	date_default_timezone_set('Europe/Kiev');
}
require_once(dirname(__FILE__).'/./Debug.php');
include_once(dirname(__FILE__). "/./Bench.php");

class Errors
{

    static $config;
    static $extraCallback;
    static $inited = false;
    static $antispam = [];

    public static function exceptionHandler($e) {
        //TODO backtrace!!!
        self::pushInfo('trace',$e->getTrace());
        $error = "Uncaught exception: " . $e->getMessage(). "\n";
        trigger_error($error);
        throw $e;
        //exit("Server Error");
    }
    public static function errorHandler($errno, $errmsg, $filename, $linenum, $vars, $backtrace = false)
    {

        if (self::isErrorLogException($filename)) {
            return "";
        }
        if (self::isErrorLogExceptionMsg($errmsg)) {
            return "";
        }
		if (self::isErrorLogExceptionUrl()) {
            return "";
        }
        // timestamp for the error entry
        $dt = date("Y-m-d H:i:s");

		// define an assoc array of error string
		// in reality the only entries we should
		// consider are 2,8,256,512 and 1024
		$errortype = array(
			1 => "Error",
			2 => "Warning",
			4 => "Parsing Error",
			8 => "Notice",
			16 => "Core Error",
			32 => "Core Warning",
			64 => "Compile Error",
			128 => "Compile Warning",
			256 => "User Error",
			512 => "User Warning",
			1024 => "User Notice",
			2048 => "Strict",
			4096 => "Recoverable Error",
			8192 => "Deprecated",
			16384 => "Javascript Error",
		);
		
		// set of errors for which a var trace will be saved
		$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

		if (false && $errno == 8192){
                    
		
			return "";
		}

		if (isset($_SESSION['testing'])) {
			echo "<testingerror>$errmsg</testingerror>";
		}

		$err = '';

		$err = '<br>';
		$err .= "<errorentry>\n";

		$err .= "\t<datetime>" . $dt . "</datetime>\n";
		$err .= "\t<errornum>" . $errno . "</errornum>\n";
		$err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
		$err .= "\t<errormsg><b>" . $errmsg . "</b><br></errormsg>\n";
		$err .= "\t<scriptname>" . $filename . "</scriptname>\n";
		$err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";
	
	
		if (self::$extraCallback != '') {
			$err .= call_user_func(self::$extraCallback);
		}

		if (defined('IS_CONSOLE')) {
			$err .= "\t<get> Console </get>\n";
		} elseif (isset($_SERVER['REQUEST_URI'])) {
			$err .= "\t<get>" . $_SERVER['REQUEST_URI'] . "</get>\n";
		} else {
			$err .= "\t<get> Another: </get>\n";
		}

        //do not spam on productions server !
		//TODO investigate what about CLI & HTTP_HOST, will it log such requests errors???
        if (
                !isset($_SERVER['HTTP_HOST']) || (
                        !Debug::isDev()
                        &&
                        !Debug::isDevServer()
                       
                         && isset(self::$antispam['errors'][md5($linenum . $errmsg)]))) {
            return true;
        }
        
        self::$antispam['errors'][md5($linenum . $errmsg)] = 1;
        if (self::config('log_backtrace')) {
            if (!$backtrace) {
                ob_start();
                if (self::config('verbose_backtrace'))
                    debug_print_backtrace();
                else
                    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

                $backtrace = ob_get_clean();
            }
            if (strlen($backtrace) > 30000) {
                $backtrace = "BACKTRACE IS TOO LARGE";
            }
        }
        $path = self::getPath();
        $extra = '';
        ob_start();
        echo "<pre>";
        if (!empty($path)) {

			echo "Path:";
			print_r($path);
		}
		echo "POST";
		print_r($_POST);

		if (isset($_SESSION['verbose']) && is_array($_SESSION['verbose'])) {
			foreach ($_SESSION['verbose'] as $key => $obj) {
				echo "$key";
				print_r($obj);
			}
		}
		echo "</pre>";
		$extra = ob_get_clean();

                if(isset($_SESSION))
                    unset($_SESSION['verbose']);


		if (isset($_SERVER['HTTP_HOST']))
			$err .= $_SERVER['HTTP_HOST'];
		$err .= self::neStartDebug('backtrace' . crc32(rand(0, 9999999)));
		$err .= "\t<backtrace><span style='font-size:12px'>" . str_replace("\n", "<br><br>", htmlspecialchars($backtrace)) . "</span></backtrace><hr>Extra:{$extra}\n";
		$err .= self::neEndDebug('backtrace');
		if (isset($_SESSION['userid'])) {
			$err .= '<br><button data-user-id="' . $_SESSION['userid'] . '" onclick="buttonRequest(this)">view information</button>';
		}
		$err .= '<div data="infoUser" style="background-color:lightgreen; padding:20px; display: none"></div>';
		//if (in_array($errno, $user_errors) && $my->DEBUG_MODE===false)
		//      $err .= "\t<vartrace>".wddx_serialize_value($vars,"Variables")."</vartrace>\n";
		$err .= "</errorentry>\n\n";
		self::neLog($err, self::config('errorfile.filename'));
		//Bench::set_time('Logging error method');
	}

	public static function isErrorLogException($filename)
	{
		$skipPages = self::config('skip.pages');

		$fullPathExceptionPages = array();
		if (is_array($skipPages) && count($skipPages) > 0) {
			foreach ($skipPages as $page) {
				$fullPathExceptionPages[] = $_SERVER['DOCUMENT_ROOT'] . $page;
			}
		}

		if (in_array($filename, $fullPathExceptionPages)) {
			return true;
		} else {
			return false;
		}
	}

	public static function isErrorLogExceptionMsg($msg)
	{
		
        $skipMessages = self::config('skip.messages');
		if (!is_array($skipMessages))
			return false;
		foreach ($skipMessages as $msg_to_skip) {
			if (strpos($msg, $msg_to_skip) !== false)
				return true;
		}
		return false;
	}

	public static function isErrorLogExceptionUrl()
	{
		$uri = $_SERVER['REQUEST_URI'];
        $skipUrls = self::config('skip.urls');
		if (!is_array($skipUrls))
			return false;
		foreach ($skipUrls as $url) {
			if (strpos($uri, $url) !== false)
				return true;
		}
		return false;
	}

	public static function fatalErrorHandler()
	{

		if ($error = error_get_last() AND $error['type'] & ( E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {

			self::errorHandler($error['type'], $error['message'], $error['file'], $error['line'], true);
		}
	}

	static function neLog($str, $filename = 'default.html')
	{

                if(self::config('enable')==false){
               //   return true;
                }
		$logPath = self::config('errorfile.path');

		if (defined('IS_CONSOLE')) {
			$domain = 'console';
			$body = "Check errors in console.";
		} else {
			$domain = $_SERVER['HTTP_HOST'];
			$body = "Check errors: https://" . $domain . "/e/index.php?getErrors";
		}

		if (self::config('notification_email') != '' && (!is_file("$logPath$filename") || date("d", filemtime("$logPath$filename")) != date("d"))) {
			mail(self::config('notification_email'), "Errors at {$domain}", $body);
		}

	    if(self::config('log_to_file')){
            if (!is_file("$logPath$filename")) {
            //todo somethin coz it apeared as recursive!!!
            neError("../logs/$filename is not accessible try to create");
                    $fp = fopen("$logPath$filename", "w"); //LOGS TASK PROCEEDING
            }
            $fp = fopen("$logPath$filename", "a"); //LOGS TASK PROCEEDING
            fwrite($fp, $str);
            fclose($fp);
        }else{
            Bench::start("LOG");
            self::writeErrorLogDb($str);
            Bench::end("LOG");
        }
	}

	public static function neDebugJavascript()
	{

		$res = '<script language="javascript">
			function toggle(caption) {
			
			var ele = document.getElementById(\'text\'+caption);
			if(!ele) return false;

			    var text = document.getElementById(caption);
			    if(typeof ele.style !== "undefined" && ele.style.display == "block") {
				ele.style.display = "none";
				text.innerHTML = "show "+caption;
			    }
			    else {
				    ele.style.display = "block";
				    text.innerHTML = "hide "+caption;
			    }
			}
		</script>';

		return $res;
	}

	public static function neStartDebug($caption)
	{
		$res = "<br><a id=\"$caption\" href=\"javascript:toggle('$caption');\">Show $caption</a>
<div id=\"text$caption\" style=\"display: none; background-color:lightgreen; padding:20px\">";

		return $res;
	}

	public static function neEndDebug($caption, $showByDefault = true)
	{
		$res = "</div>";
		if ($showByDefault) {
			$res .= '<script language="javascript">
                            toggle(\'' . $caption . '\')
                    </script>';
		}
		return $res;
	}

	public static function logJavascriptError($post)
	{
		$errmsg = $post['error'];
		$stackTrace = $post['stackTrace'];
		$userAgent = $post['userAgent'];
		$filename = isset($post['path']) ? $post['path'] : $post['filename'];
		$linenum = $post['line'];
//		$column = $post['column'];
		$mobileDevice = $post['mobileDevice'];
		$mobile = $post['mobile'];
		$browserName = isset($post['browserName']) ? " Real Browser Name: $post[browserName], " : "";

		$errmsg .= " Mobile: $mobileDevice. Mobile Device: $mobile.$browserName User Agent. $userAgent";

		self::errorHandler(16384, $errmsg, $filename, $linenum, $vars = false, $stackTrace);

		return true;
	}

	public static function pushCurrentUrl()
	{
		if (self::config('log_user_path') === false)
			return false;

		$maxUrls = 10;
		$currentUrl = (isset($_SERVER['REQUEST_URI']))?$_SERVER['REQUEST_URI']:"console";
		$urls = array();
		$maxId = 1;
		foreach ($_COOKIE as $name => $value) {
			if (strpos($name, 'path') !== false) {
				$id = intval(str_replace('path', '', $name));
				if ($id > $maxId)
					$maxId = $id;
				$urls[$id] = 1;
			}
		}
		$newId = $maxId + 1;
		setcookie("path{$newId}", $currentUrl, time() + 86400, '/');


		$serialized = serialize($_COOKIE);
		$size = strlen($serialized);
		$sizeKbytes = ($size * 8 / 1024);

		if (count($urls) > $maxUrls || $sizeKbytes > 3) {
			$i = 0;
			ksort($urls);
			foreach ($urls as $id => $dummy) {

				$i++;
				if ($i > $maxUrls / 2 && !($sizeKbytes > 3)) { //remove haf of cookies or all
					break;
				}

				setcookie("path{$id}", "", time() - 2592000, '/');
			}
		}
	}

	static function getPath()
	{
		if (self::config('log_user_path') === false)
			return false;
		$urls = array();
		foreach ($_COOKIE as $name => $value) {
			if (strpos($name, 'path') !== false) {
				$id = intval(str_replace('path', '', $name));

				$urls[$id] = $value;
			}
		}

		ksort($urls);
		return $urls;
	}

	static function pushInfo($key, $object)
	{
		if (!isset($_SESSION['verbose']))
			$_SESSION['verbose'] = array();
		$_SESSION['verbose'][$key] = $object;
	}

    public static function init($config = false)
    {
        if ($config === false) {
            $config = include_once(dirname(__FILE__) . "/./config.php");
            self::setConfig($config);
        } else {
            self::setConfig($config);
        }
        $oldErrorHandler = set_error_handler("Errors::errorHandler");
        $oldFatalErrorHandler = register_shutdown_function("Errors::fatalErrorHandler");
        //FIXME IF I ENABLE wordpress homepage is broken
        //!!!!!!!!!!!!!!!
        if(self::config('log_exceptions'))
            set_exception_handler("Errors::exceptionHandler");
        if (!defined("API_MODE"))
            Errors::pushCurrentUrl();
        else {
            if (!API_MODE)
                Errors::pushCurrentUrl();
        }
        self::$inited = true;
    }

	static function setExtraCallback($function)
	{
		self::$extraCallback = $function;
	}

	static function setConfig($config)
	{
		//return true;
		self::$config = $config;
	}

	public static function config($name)
	{
		$arr = self::$config;
		$keys = explode('.', $name);
		foreach ($keys as $key) {
			if (isset($arr[$key]))
				$arr = &$arr[$key];
			else
				return false;
		}
		return $arr;
	}

	static function createDb()
	{
		$filename = self::config('errorfile.path') . self::config('errorfile.filename');
		$sqlite = new SQLite3($filename);

		if (!$sqlite) {
			return;
		}
		$sql = 'PRAGMA encoding = "UTF-8";CREATE TABLE errorentrys(id INTEGER PRIMARY KEY, errorentry TEXT, domain TEXT );';
		$sqlite->exec($sql);
                return $sqlite;
	}

    public static function writeErrorLogDb($str)
    {
        $filename = self::config('errorfile.path') . self::config('errorfile.filename');
        //	exit($filename);
        if (!is_file($filename)) {
            self::createDb();
        } else {
            $sqlite = new SQLite3($filename);
            if (!$sqlite) {
                return;
            }
        }

//            if(!self::isTable($sqlite))
//            {
//                $sql = 'PRAGMA encoding = "UTF-8";CREATE TABLE errorentrys(id INTEGER PRIMARY KEY, errorentry TEXT );';
//                $sqlite->exec($sql);
//            }
//	    if(strpos($str,'A discussion that')!==false){
//			exit($str);
//		}
		$sqlite->busyTimeout(5000);
		$sql = "PRAGMA synchronous = NORMAL;PRAGMA journal_mode=WAL;PRAGMA busy_timeout = 5000;";
		$sqlite->exec($sql);

		$sql = 'INSERT INTO errorentrys ("errorentry", "domain")  VALUES(?,?)';
		$stmt = $sqlite->prepare($sql);
		if ($stmt === false) {
			self::createDb();
			echo "<b>Error</b>: $str";
		} else {
			$website = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : "unknown/console";
			$stmt->bindValue(1, $str, SQLITE3_TEXT);
			$stmt->bindValue(2, $website, SQLITE3_TEXT);
			$stmt->execute()->finalize();
			$sqlite->close();
			unset($sqlite);
		}
	}

	static function isTable($sqlite)
	{
		$result = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name='errorentrys'");
		$res = $result->fetchArray();
		if ($res['name'] != 'errorentrys')
			return false;
		return true;
	}

	//loggin wp mysql errors
	function db_debug_log2()
	{

		//WP already stores query errors in this obscure
		//global variable, so we can see what we've ended
		//up with just before shutdown
		global $EZSQL_ERROR;

		try {
			//proceed if there were MySQL errors during runtime
			if (is_array($EZSQL_ERROR) && count($EZSQL_ERROR)) {
				//and lastly, add the error messages with some line separations for readability
				foreach ($EZSQL_ERROR AS $e) {
					trigger_error(implode("\n", $e));
				}
			}
		} catch (Exception $e) {
			
		}

		return;
	}

//add_action('shutdown', 'db_debug_log');
	public static function logInfo($obj)
	{
		if (is_array($obj) || is_object($obj)) {
                        $data = "<pre>";
			$data .= print_r($obj, true);
                        $data .= "</pre>";

		} else {
			$data = $obj;
		}

		$backtrace = '';
		if (self::config('log_backtrace')) {
			//if (!$backtrace) {
			ob_start();
			if (self::config('verbose_backtrace'))
				debug_print_backtrace();
			else
				debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

			$backtrace = ob_get_clean();
			//  }
			if (strlen($backtrace) > 30000) {
				$backtrace = "BACKTRACE IS TOO LARGE";
			}
		}

		self::neLog($data . $backtrace, self::config('errorfile.filename'));
	}

}

?>