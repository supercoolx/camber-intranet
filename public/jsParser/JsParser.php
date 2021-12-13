<?php
define("JS_PARSER_CONFIG", $_SERVER['DOCUMENT_ROOT']."/./protected/config/config.js.php");

//TODO JsParser make method
//	<script src="/scripts/jquery.alupka.js?ver=" type="text/javascript"></script>
//        <script type="text/javascript" src="/scripts/init.js?ver="></script>

class JsParser
{

	private static $hideProductionSelector = false;

	//displayPage function will replace renderPage
	public static function displayPage($pagename, $templatePath, $data = false)
	{

		$pgdp = $egdp = "";

		ob_start();
		require($templatePath);
		$content = ob_get_contents();
		ob_end_clean();
		if(Debug::isDevServer()){
			$content .= "<span style='color:red'>Template: $templatePath</span>";
		}
		echo self::renderPage($pagename, $content, $data);
	}

	public static function displayPrintPage($pagename, $templatePath, $data = false)
	{

		$pgdp = $egdp = "";
		$hidecsel = self::$hideProductionSelector;
		$_GET['pf'] = 1;
		ob_start();
		require($templatePath);
		$content = ob_get_contents();
		ob_end_clean();
		if(Debug::isDevServer()){
			$content .= "<span style='color:red'>Template: $templatePath</span>";
		}
		echo self::renderPage($pagename, $content, $data);
	}

	public static function renderPage($pagename, $content, $data = false)
	{
		$hidecsel = self::$hideProductionSelector;

		ob_start();

		if (isset($_GET['pf']))
			include PFHEAD;
		else
			include SITEHEAD;


		self::parseJsRoles($content);
		echo $content;

		if (isset($_GET['pf']))
			include PFFOOT;
		else
			include SITEFOOT;

		$pageHtml = ob_get_contents();
		ob_end_clean();

		return $pageHtml;
	}

	public static function renderPrintPage($pagename, $content)
	{
		$hidecsel = self::$hideProductionSelector;
		//for back compatability
		$_GET['pf'] = 1;

		return self::renderPage($pagename, $content);
	}

	public static function hideSelector()
	{
		self::$hideProductionSelector = true;
	}

	//remove later
	public static function render($templatePath, $data = false)
	{


		
		ob_start();
		require($templatePath);
		$content = ob_get_contents();
		ob_end_clean();
		if(Debug::isDevServer()){
			$content .= "<span style='color:red'>Template: $templatePath</span>";
		}
		self::parseJsRoles($content);
		return $content;
	}
	//TODO remove this fnction and refactor its usage
	public static function display($templatePath, $dataObject = array())
	{
		if (is_array($dataObject)) {
			foreach ($dataObject as $name => $value) {
				$$name = $value;
			}
		}

		ob_start();
		require($templatePath);
		$content = ob_get_contents();
		ob_end_clean();

		if(Debug::isDevServer()){
			$content .= "<span style='color:red'>Template: $templatePath</span>";
		}
		
		self::parseJsRoles($content);
		echo $content;
	}

	public static function pack($array)
	{
		$object = new stdClass();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$value = self::pack($value);
			}
			$object->$key = $value;
		}
		return $object;
	}

	public static function injectJsRole($content,$pluginName,$arg=false)
	{
		//TODO make it static var
                $JsPlugins = include(JS_PARSER_CONFIG);
		//$content = "";
		$plugins = $JsPlugins[$pluginName];
                
		$argString = ($arg===false) ? "":"$arg";
		$argStringForCallback = ($arg===false) ? "":",$arg";
		$injectCode = "\r\n<!-- start $pluginName inserted by tmpl engine -->";

		if (isset($plugins[0]['styles']))
			$injectCode .= '<link rel="stylesheet" type="text/css" href="' . $plugins[0]['styles'] . '" media="none" onload="if(media!=\'all\')media=\'all\'">';
		if (isset($plugins[1]['styles']))
			$injectCode .= '<link rel="stylesheet" type="text/css" href="' . $plugins[1]['styles'] . '" media="none" onload="if(media!=\'all\')media=\'all\'">';
		if (isset($plugins[2]['styles']))
			$injectCode .= '<link rel="stylesheet" type="text/css" href="' . $plugins[2]['styles'] . '" media="none" onload="if(media!=\'all\')media=\'all\'">';



		if (isset($plugins[0]['before']))
			$injectCode .= "<script>" . $plugins[0]['before'] . "</script>";
		if (isset($plugins[1]['before']))
			$injectCode .= "<script>" . $plugins[1]['before'] . "</script>";
		if (isset($plugins[2]['before']))
			$injectCode .= "<script>" . $plugins[2]['before'] . "</script>";

		if (isset($plugins[0]['onready']) && $plugins[0]['onready'] == true) {
			$wrapperLeft = '$(document).ready(function () {';
			$wrapperRight = '});';
		} else {
			$wrapperLeft = $wrapperRight = '';
		}
        //no extra js file
		//print_r($plugins);
		if (count($plugins) == 1 && !isset($plugins[0]['script'])) {
			$injectCode .= "\r\n<!--" . $pluginName . " inserted by tmpl engine --><script>" . $plugins[0]['init'] . "($argString); console.log('nfc inited '+'".$plugins[0]['init']."');</script>";
		}elseif (count($plugins) == 1) {
			$injectCode .= "\r\n<!--" . $pluginName . " inserted by tmpl engine --><script>{$wrapperLeft}loadScriptOnce('" . $plugins[0]['script'] . "?ver=" . SCRIPTS_VERSION. "'," . $plugins[0]['init'] . "{$argStringForCallback}){$wrapperRight} ;console.log('nfc inited '+'".$plugins[0]['init']."');</script>";
		}
		//recurring loading
		if (count($plugins) == 2) {

			$injectCode .= "\r\n<!--" . $pluginName . " inserted by tmpl engine --><script> {$wrapperLeft}loadScriptOnce('" . $plugins[0]['script'] . "?ver=" . SCRIPTS_VERSION.  "', function(){" .
					" console.log('loading next-'+'".$plugins[1]['init']."'); loadScriptOnce('" . $plugins[1]['script'] . "?ver=" . SCRIPTS_VERSION.  "'," . $plugins[1]['init'] . "{$argStringForCallback})"
					. "}); {$wrapperRight} ;console.log('nfc inited '+'".$plugins[1]['init']."');</script>";
		}

		//recursive loading
		if (count($plugins) == 3) {

			$injectCode .= "\r\n<!--" . $pluginName . " inserted by tmpl engine --><script> {$wrapperLeft}loadScriptOnce('" . $plugins[0]['script'] . "?ver=" . SCRIPTS_VERSION.  "', function(){" .
					" loadScriptOnce('" . $plugins[1]['script'] . "?ver=" . SCRIPTS_VERSION.  "',function(){" .
					"loadScriptOnce('" . $plugins[2]['script'] . "?ver=" . SCRIPTS_VERSION.  "',".$plugins[2]['init'] . "{$argStringForCallback})})"
					. "}); {$wrapperRight} ;console.log('nfc inited  '+'".$plugins[2]['init']."');</script>";
		}
              //  echo strpos($content,'</html>');
               // echo "-t-";
              //  echo $content;
            //    exit("-");
                $content = str_replace('</html>', $injectCode.'</html>', $content);
		return $content;
	}

	public static function parseJsRoles(&$content)
	{
		//global $JsPlugins;
//exit("");

                $JsPlugins = include(JS_PARSER_CONFIG);
              //  echo JS_PARSER_CONFIG;
               // print_r($JsPlugins);
             //   exit("");
		//if(strpos($content,'parsed by tmpl engine')!==false)
		//		return true;
		//$content .= "<!-- parsed by tmpl engine-->";

		if (!self::isAjaxRequest()) {
			$wrapperLeft = '$(document).ready(function () {';
			$wrapperRight = '});';
		} else {
			$wrapperLeft = $wrapperRight = '';
		}

		foreach ($JsPlugins as $pluginName => $plugins) {
			
			if ((
                                strpos($content, 'js-role=\'' . $pluginName . '\'') !== false
				|| strpos($content, 'js-role=\'' . $pluginName . '(') !== false
			        || strpos($content, 'js-role="' . $pluginName . '"') !== false
			        || strpos($content, 'js-role="' . $pluginName . '(') !== false
			    )
				&& strpos($content, $pluginName . ' inserted by') === false) {

					if(strpos($content, 'js-role=\'' . $pluginName . '(') !== false
						|| strpos($content, 'js-role="' . $pluginName . '(') !== false){
						$pattern = '/js-role=[\'"]' . $pluginName . '\(([^\)]*)\)[\'"]/';
					
						$test = preg_match($pattern,$content,$matches);

						if($test>0){
							$arg = $matches[1];
						
							$content = self::injectJsRole($content,$pluginName,$arg);
						}
					}else{
						$content = self::injectJsRole($content,$pluginName);
					}
			}
		}

         //TODO add validation to detect wrong attributy, misspelling etc
                //TODO rewrite for standalone
		if (strpos($content, 'js-visibility-depends-on=') !== false) {
			$content .= "\r\n<!--visibility inserted by tmpl engine --><script>Alupka.parseVisibility();</script>";
		}



		return true;
	}

	public static function safe($str){
		$str = str_replace('"','\"',$str);
		return $str;
	}

	public static function isDialog(){
		if(isset($_GET['CONTROLJS-DIALOG']))
			return true;
		else
			return false;
	}

        private static function isAjaxRequest()
        {
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		return true;
	else
		return false;
        }

}

?>
