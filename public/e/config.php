<?php

$allowedIPsForDebug = array("176.115.99.213", "95.158.42.104", "95.158.53.99", "95.158.36.75", "95.132.18.105", "95.158.23.204", "146.120.222.2", "127.0.0.1", "91.200.126.45", "78.111.189.238", "91.247.226.71");
$errorsConfig = [
        'enable'=>1,
	'code_version' => (defined("SCRIPTS_VERSION")) ? SCRIPTS_VERSION: 'NA', //use it
	'log_to_file'=>false,
        'errorfile' => [
		'path' => dirname(__FILE__) . "/",
		'filename'=>"errors.log",
	],
	'log_user_path' => true,
	'log_backtrace' => true,
	'verbose_backtrace' => false,
	'allowed_ips' => $allowedIPsForDebug,
	'notification_email'=>'dmi2nfc@gmail.com',
        'log_exceptions'=>true,
	'skip'=>[
		'pages' => [
				'/aasd765Jas6543/web/CHttpSession.php',
    				'/protected/extensions/bootstrap/widgets/TbDataColumn.php',
                                '/protected/extensions/mpdf/mpdf.php',
                                '/aasd765Jas6543/web/auth/CDbAuthManager.php',
                    'protected/modules/dbe/models/DbeFlaeche.php', //TODO remove later
                    '/protected/modules/dbe/models/DbeFlaeche.php',//TODO remove later
		],
		'messages' => [
                    'Function mcrypt_encrypt() is deprecated',
                    'Uncaught exception: CDbConnection failed to open the DB connection.'
		],
		'urls'=> []
	]

];
return $errorsConfig;
?>