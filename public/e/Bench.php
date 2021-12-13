<?php

class Bench
{
	private static $timing;
	private static  $last_time;
	private  static $first_time;
	private  static $times;
        private  static $locations;
	private  static $total_time;
	private static  $memory;
	private  static $SHOW_TIME = 1;
        private  static $hideSmall = 0;
	private static  $hards = array();

	public static function start_timing()
	{
		$mi = microtime();
		list($msec, $sec) = explode(" ", $mi);
		self::$last_time = $sec + $msec;
		self::$first_time = $sec + $msec;
	}

	public static function reset_time()
	{
		if (self::$SHOW_TIME == 1) {

			$mi = microtime();
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - self::$last_time;
			self::$last_time = $cur_time;
		}
	}
        public static function start($metka)
	{

		if (self::$SHOW_TIME == 1) {

			$mi = microtime();
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
		

			self::$times[$metka]['start'] = $cur_time;
		
		}
	}
        public static function end($metka)
	{

		if (self::$SHOW_TIME == 1) {

			$mi = microtime();
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			
			self::$times[$metka]['value'] =  ((isset(self::$times[$metka]['count']))) ?  self::$times[$metka]['value']  + ($cur_time - self::$times[$metka]['start']) : ($cur_time - self::$times[$metka]['start']);
                        self::$times[$metka]['count'] =  (isset(self::$times[$metka]['count'])) ? self::$times[$metka]['count']  + 1 : 1;
		
		}
	}
	public static function set_time($metka)
	{

		if (self::$SHOW_TIME == 1) {

			$mi = microtime();
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - self::$last_time;
			self::$last_time = $cur_time;

			self::$times[$metka] = $riznycja;
			self::$memory[$metka] = round(memory_get_usage() / 1000000, 1);

                        $bt = debug_backtrace();
                        $caller = array_shift($bt);


                        self::$locations[$metka] = $caller['file']. " " . $caller['line'];

		}
	}

	public static function show_times()
	{
		
		self::$timing .= "<table>";
		self::$timing .= "<tr>
			<th>Block</th>
			<th>Time</th>
			<th>Percent</th>
			<th>Memory</th>
			<th></th>
			</tr>";
	
			if (self::$SHOW_TIME == 1 && isset(self::$times)) {
				foreach (self::$times as $key => $value) {
                                    $count = "";
                                    if(is_array($value)){
                                        $count = $value['count'];
                                        $value = $value['value'];

                                    }
                                    
					$mem = (isset(self::$memory[$key])) ? self::$memory[$key]:0;
					$q = $value * 1000;
					$q1 = self::$total_time * 1000;
					$per = ($q / $q1) * 100;
					$per = (int) $per;
                                        //TODO make config hide non important
                                   //     if(self::$hideSmall && $per<1)
                                     //       continue;
					if ($per > 10) {
						self::$hards[$value] = "block: $key ($count); exectime: $value";
					}
					if ($per > 2) {
						$colorClass= 'red';
						$color = "<font sty=red>";
						$colorClose = "</font>";
					} else {
						$colorClass = 'black';
						$color = "";
						$colorClose = "";
					}

					self::$timing .= "<tr style='color:{$colorClass}'>

							<td>
								$key ($count)
							</td>
							<td>
								".round($value,3)."|
							</td>
							<td>
								$per%
							</td>
							<td>
								$mem
							</td>
							<td>
								".((isset(self::$locations[$key])) ? self::$locations[$key] : "" )."
							</td>
							";
				}
			}
	
		self::$timing .= "</table>";
	}

	public static function logStats()
	{
		if (isset($_SERVER['HTTP_REFERER']))
			$data['referer'] = $_SERVER['HTTP_REFERER'];
		else
			$data['referer'] = "N/A";
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$data['remote_address'] = $_SERVER['REMOTE_ADDR'];
	
	}
	public static function getTotal(){
		//return self::
	}
	public static function logExecTime()
	{
	

		self::logStats();
		if (self::$SHOW_TIME == 1) {

			$mi = microtime();
			//echo "<br>st:".$mi.":";
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - self::$first_time;
			self::$total_time = $riznycja;
			//echo "Total:"."|exec time:".$riznycja;
			// setting stats...
			$current_page = $_SERVER['REQUEST_URI'];
			$today = getdate();
			$day = $today['mday'];
			$month = $today['mon'];
			$year = $today['year'];
			if (strlen($month) == 1)
				$month = "0" . $month;
			$mysql_date = $year . "-" . $month . "-" . $day . " $today[hours]:$today[minutes]";

			//nfc re certain block

			$value = (isset(self::$times['hits recording'])) ? self::$times['hits recording'] : "";
			//echo " $value";
			$q = $value * 1000;
			$q1 = self::$total_time * 1000;
			$per = ($q / $q1) * 100;
			$per = (int) $per;
			$hit_per = $per;

			$value = self::$times['session_init'];
			//echo " $value";
			$q = $value * 1000;
			$q1 = self::$total_time * 1000;
			$per = ($q / $q1) * 100;
			$per = (int) $per;
			$ses_per = $per;

			//$writing_to_cache = $writing_to_cache * 2;
			//$c_comment.= $c_comment . "$ses_per";
			//session minus
			if (isset($_SESSION['log_event']))
				$events = $_SESSION['log_event'];
			$riznycja = $cur_time - self::$first_time - $value;
			if ((LOG_EXEC_TIME) || isset($_GET['push_log'])) {
				$_SESSION['log_exec_sql'] = "insert delayed into tbl_exectimes(e_pagename,e_etime,e_date,e_flag1,e_flag2,e_comment,e_events)  values('" . addSlashes($current_page) . "','" . $riznycja . "','" . $mysql_date . "','" . $taking_from_cache . $writing_to_cache . "','" . $hit_per . "','" . $c_comment . "','nfc_events')";
				execSQL("insert delayed into tbl_exectimes(e_pagename,e_etime,e_date,e_flag1,e_flag2,e_comment,e_events)  values('" . $current_page . "','" . $riznycja . "','" . $mysql_date . "','" . $taking_from_cache . $writing_to_cache . "','" . $hit_per . "','" . $c_comment . "','" . $events . "')");
				//if (isset($_GET['push_log']))
				//  echo "insert delayed into tbl_exectimes(e_pagename,e_etime,e_date,e_flag1,e_flag2,e_comment)  values('".$current_page."','".$riznycja."','".$mysql_date."','".$taking_from_cache.$writing_to_cache."','".$hit_per."','".$c_comment."')";
			}
	
		}
	}

	public static function show_total_time()
	{
		

		if (self::$SHOW_TIME == 1) {
			$mi = microtime();
			//echo "<br>st:".$mi.":";
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - self::$first_time;
			self::$total_time = $riznycja;
			self::$timing = "<hr>Total:" . "|exec time:" . $riznycja;
			//to do reimplement
			if (isset(self::$times['session_init']))
				self::$timing .= "<br/>Pure:" . "|exec time:" . ($riznycja - self::$times['session_init']);
			// setting stats...
			$current_page = $_SERVER['PHP_SELF'];
			$today = getdate();
			$day = $today['mday'];
			$month = $today['mon'];
			$year = $today['year'];
			if (strlen($month) == 1)
				$month = "0" . $month;
			$mysql_date = $year . "-" . $month . "-" . $day;
		}
		self::show_times();
              
		return self::$timing;
	}

}

function closure_dump(Closure $c) {
    $str = 'function (';
    $r = new ReflectionFunction($c);
    $params = array();
    foreach($r->getParameters() as $p) {
        $s = '';
        if($p->isArray()) {
            $s .= 'array ';
        } else if($p->getClass()) {
            $s .= $p->getClass()->name . ' ';
        }
        if($p->isPassedByReference()){
            $s .= '&';
        }
        $s .= '$' . $p->name;
        if($p->isOptional()) {
            $s .= ' = ' . var_export($p->getDefaultValue(), TRUE);
        }
        $params []= $s;
    }
    $str .= implode(', ', $params);
    $str .= '){' . PHP_EOL;
    $lines = file($r->getFileName());
    for($l = $r->getStartLine(); $l < $r->getEndLine(); $l++) {
        $str .= $lines[$l];
    }
    return $str;
}
