<?php


if(is_file(dirname(__FILE__) . "/../config.php")){
    include_once(dirname(__FILE__) . "/../config.php");
}
if(is_file(dirname(__FILE__) . "/../wp-db.php")){
    include_once(dirname(__FILE__) . "/../wp-db.php");
}
	
include_once('SQLitePDO.php');	
include_once("Bench.php");
include_once("Errors.php");
Errors::init();
define('PAGE_SIZE',20);

if (isset($_POST['setJsError'])) {
	Errors::logJavascriptError($_POST);
	exit();
}
ini_set("display_errors","1");
error_reporting(E_ALL);

$ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];

if (!Debug::isDev()) {
	echo "Access Denied";
	exit("");
}

echo "<body>";
echo "<head></head>";

if(isset($_GET['show_server_data'])){
    if (!extension_loaded('imagick')){
            echo 'Imagick not installed<br>';
    }
    phpinfo();
}
$website = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : "unknown/console";
echo $website,'<br>';
echo 'DOCUMENT_ROOT = '.$_SERVER['DOCUMENT_ROOT'].'<br>';
echo "<b>DB file".Errors::config('errorfile.path').Errors::config('errorfile.filename')."<br>";
echo date('d-m-Y  H:i:s') . "<br></b>";
if(isset($_GET['decode'])){
	echo decode($_GET['val']);
	exit("");
}
if(isset($_GET['hash'])){
	echo md5_dir(dirname(__FILE__)."/../protected");
	exit("");
}
echo "Check1.1<br>";
if (isset($_GET['getInfoUser'])) { exit;
    $time = strtotime($_GET['date']);
    $id = (int) $_GET['getInfoUser'];
    $method = urldecode($_GET['method']);
    $filename = Errors::config('errorfile.path') . Errors::config('errorfile.filename');
    $sqlite = new SQLite3($filename);
    $time1 = $time - 600; $time2 = $time + 120;
    $sql = 'SELECT * FROM requestentrys WHERE date BETWEEN '.$time1.' AND '.$time2.' AND user_id='.$id.' ORDER BY date DESC ';
//    die($sql);
    $result = $sqlite->query($sql);
    $out = '';
    while($res = $result->fetchArray(SQLITE3_ASSOC)){
        if ($out === '')
            $out .= 'Roles: ' . $res['user_roles'] . '<br>';

        $background = '';
        if ($time >= $res['date']-3 AND $res['date']+3 >= $time AND strripos($res['method'], $method))
            $background = 'background-color: red;';

        $caption = 'request' . crc32(rand(0, 9999999)); //$res['date'];
        $out .= '<div style="float: left; ' .$background. '">';
        $out .= date("Y-m-d H:i:s", $res['date']) . ' ';
        $out .= $res['method'] . ' ';
        $out .= '<a id="' . $caption . '" href="javascript:toggleRequest(\'' . $caption . '\');"> detail</a><br> ';
        $out .= ' </div>';
        $out .= '<br>';
        $out .= '<div id="text' . $caption . '" style="display: none; background-color:lightgreen; padding:20px">';
        $out .= '<pre>';
        $out .= 'Request: '. $res['requestentry'] . '<br>';
        $out .= '</pre>';
        $out .= '</div>';
    }

    exit($out);
}

if (isset($_GET['getErrors'])) :
	echo "Current code version: ".Errors::config('code_version');
	echo '<br>
	    <div class="row">
		<div class="col-sm-3">
		    <a target="_blank" href="?clearErrors">Clear Error Log</a>
                    <a target="_blank" href="?show_server_data">Show server data</a>

		</div>
		<div class="col-sm-5">
		    <div class="btn-group btn-group-toggle default-groupping" data-toggle="buttons">
			<label class="btn btn-outline-primary active">
			  <input type="radio" name="options" id="default-group" group-by="default" autocomplete="off" checked> Default
			</label>
			<label class="btn btn-outline-primary">
			  <input type="radio" name="options" id="group-by-date" group-by="date" autocomplete="off"> Group By Date
			</label>
			<label class="btn btn-outline-primary">
			  <input type="radio" name="options" id="group-by-name" group-by="errorMsg" autocomplete="off"> Group By Name
			</label>
			<label class="btn btn-outline-primary">
			  <input type="radio" name="options" id="group-by-domain" group-by="domain" autocomplete="off"> Group by Domain
			</label>
		      </div>
		</div>
	    </div>';
	
	echo "Decode: <input id='number_to_decode' type='text'><span id='decoded_result'></span>";
	//echo "
	?>

<!--<script src="/scripts/jquery-1.11.2.min.js" type="text/javascript"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.js" type="text/javascript"></script>

<script src="errors.js" type="text/javascript"></script>

<!--<link rel="stylesheet" type="text/css" href="/scripts/bootstrap/lib/bootstrap3/dist.3.3/css/bootstrap.css?v3.3.2">

<script src="/scripts/bootstrap/lib/bootstrap3/dist.3.3/js/bootstrap.min.js"></script>-->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<!-- Optional theme -->
<!-- Latest compiled and minified JavaScript -->

<!--<script src="/scripts/vcb.js" type="text/javascript"></script>-->
<script>
if(typeof setFavicon !=='undefined')
	setFavicon('favicon-error.ico');
$(document).ready(function(){
	$( document ).on( "change", "#number_to_decode", function() {
		$.ajax({
			url: '/e/index.php?decode&val='+$('#number_to_decode').val(),
			type: 'get',
			success: function (data) {
				$('#decoded_result').html(' => '+data);
			}
	});
	});
});
function loadNextPage(page = 0){
    $('#loadPage').remove();
    $.ajax({
	url: '/e/index.php?loadNextPage&page='+ page,
	type: 'get',
	success: function (data) {
            $('.all-errors').html(data);
	}
    });
}


function buttonRequest(param) {
    //var button = $('[data-user-id=" '+userId+ '"]');
    var block = $($(param).parent()).find('[data="infoUser"]')
    var date = $($(param).parent()).find('datetime').text();
    var get = $($(param).parent()).find('get').text();
    var id = $(param).attr('data-user-id');

    console.log(date);
    console.log(id);
    console.log(encodeURI(get));

    if ($(block).html() === '') {
        $.ajax({
            url: "/e/index.php?getInfoUser="+ id +'&method=' + encodeURI(get) + '&date='+ date,
            type: "get",
            success: function (data) {
                $(block).html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    if ($(block).css('display') === 'block') {
        $(block).css('display', 'none')
    } else {
        $(block).css('display', 'block');
    }
}

function toggleRequest(caption) {

    var ele = document.getElementById('text'+caption);
    if(!ele) return false;

    var text = document.getElementById(caption);
    if(typeof ele.style !== "undefined" && ele.style.display == "block") {
        ele.style.display = "none";
        text.innerHTML = "detail";
    }
    else {
        ele.style.display = "block";
        text.innerHTML = "close";
    }
}

</script>
<?php
echo "Mine1";
	echo Errors::neDebugJavascript();
	echo '<div class="all-errors">';	
       readErrorLogDb();
	echo '</div>';
	exit();
endif;
if (isset($_GET['clearErrors'])) {
/*	$fp = fopen(ERROR_LOG_PATH ."/errors.html", "w"); //LOGS TASK PROCEEDING
	fwrite($fp, "");
	fclose($fp);
 */
        $filename = Errors::config('errorfile.path') . Errors::config('errorfile.filename');
		if(!is_file($filename)){
			echo "DB filename not found: $filename";
			exit();
		}
        $sqlite = new SQLite3($filename);
        $sqlite->query('DELETE FROM errorentrys WHERE 1');
        $sqlite->close();
//        unlink($filename);
	echo "Log file has been emptied";
	exit();
}

if (isset($_GET['clearCache'])) {
	//User::resetSettingsHash();
	//echo "Cache files has been deleted";
	//exit();
}
echo "Check2";
if(isset($_GET['genError'])){
    $errorstype = [1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384];
    for($i=0;$i<100;$i++){
        $j = $errorstype[rand(0,count($errorstype)-1)];
        Errors::errorHandler($j, "errmsg $j", "filename", "linenum", "vars");
        usleep(10);
        echo $j.'<br>';
    }    
}

if(isset($_GET['loadNextPage'])){
    readErrorLogDb($_GET['page']);
    exit();
}
echo "Check3";
function readErrorLogDb($page = 0)
{
    echo "read1";
    $filename = Errors::config('errorfile.path') . Errors::config('errorfile.filename');
	   // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:'.$filename);
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);

    $sqlite = new SQLite3($filename);
    $result = $sqlite->query('SELECT COUNT(*) FROM errorentrys');
    $res = $result->fetchArray();
    $records = $res[0];
    $nextpage = $page + 1;
    if(($page * PAGE_SIZE) > $records) {
        $sqlite->close();
        exit("");
    }
    $offset = $page * PAGE_SIZE;



    $sql = "SELECT errorentry FROM errorentrys ORDER BY id DESC LIMIT $offset,". PAGE_SIZE;
    $result = $sqlite->query($sql);

	
echo "QUERY";
	 $result = $file_db->query("SELECT errorentry, domain FROM errorentrys ORDER BY id DESC LIMIT $offset,". PAGE_SIZE);
        foreach($result as $res){ 
            $temp = $res['errorentry'];
            //$temp = substr($errorentry,0,strrpos($errorentry,'</get>')+strlen('</get>'));
            //$temp .= '<domain>'.$res['domain'].'</domain>';
            //$temp .= substr($errorentry,strrpos($errorentry,'<br><button'));
            echo $temp;
//            ob_start();
//            echo $res['errorentry'];
//            echo '<p>',$res['domain'],'</p>';
//            ob_end_flush();
        }    
    $sqlite->close();
    echo "Everything OK";


    $out = '<nav aria-label="Page navigation example">
              <ul class="pagination justify-content-center">';

    if ($page <= 0)
        $out .= '<li class="page-item disabled">
                  <a class="page-link" tabindex="-1">Previous</a>
                </li>';
    else
        $out .= '<li class="page-item">
                  <a class="page-link" href="javascript:loadNextPage('.($page-1).')">Previous</a>
                </li>';

    $temPage = 0;
    while (($temPage * PAGE_SIZE) < $records) {
        $active = '';
        if ($page == $temPage)
            $active = 'active';
        $out .= '   <li class="page-item '.$active.'">
                        <a class="page-link" href="javascript:loadNextPage('.$temPage.')">'.($temPage+1).'</a>
                    </li>';
        $temPage++;
    }

    if (($nextpage * PAGE_SIZE) > $records)
        $out .= '<li class="page-item disabled">
                    <a class="page-link" tabindex="-1">Next</a>
                 </li>';
    else
        $out .= '<li class="page-item">
                    <a class="page-link" href="javascript:loadNextPage('.$nextpage.')">Next</a>
                 </li>';

    $out .= '  </ul>
            </nav>';

    echo $out;

    exit("<script>$('#MesWait').remove();callboardErrors.init();</script>");
    //echo '<div id="loadPage"><script> loadNextPage('.$nextpage.');</script></div>';
    //exit();
}
function md5_dir($dir)
{
    if (!is_dir($dir))
    {
        return false;
    }

    $filemd5s = array();
    $d = dir($dir);

    while (false !== ($entry = $d->read()))
    {
        if ($entry != '.' && $entry != '..')
        {
             if (is_dir($dir.'/'.$entry))
             {
                 $filemd5s[] = md5_dir($dir.'/'.$entry);
             }
             else
             {
                 echo "\r\n".$dir.'/'.$entry;
                 $filemd5s[] = md5_file($dir.'/'.$entry);
             }
         }
    }
    $d->close();
    return md5(implode('', $filemd5s));
}