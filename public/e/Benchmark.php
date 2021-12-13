<?php

class Benchmark
{


	var $last_time;
	var $first_time;
	var $times;
	var $total_time;
	var $memory;
	var $SHOW_TIME = 1;

	function start_timing()
	{
		$mi = microtime();
		list($msec, $sec) = explode(" ", $mi);
		$this->last_time = $sec + $msec;
		$this->first_time = $sec + $msec;
	}

	function reset_time()
	{
		if ($this->SHOW_TIME == 1) {

			$mi = microtime();
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - $this->last_time;
			$this->last_time = $cur_time;
		}
	}

	function set_time($metka)
	{

		if ($this->SHOW_TIME == 1) {

			$mi = microtime();
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - $this->last_time;
			$this->last_time = $cur_time;

			$this->times[$metka] = $riznycja;
			$this->memory[$metka] = round(memory_get_usage() / 1000000, 1);
		}
	}

	function show_times()
	{
		global $timing, $hit_per, $hards;

		if ($this->SHOW_TIME == 1) {
			if (isset($this->times)) {
				foreach ($this->times as $key => $value) {
					$mem = $this->memory[$key];
					$q = $value * 1000;
					$q1 = $this->total_time * 1000;
					$per = ($q / $q1) * 100;
					$per = (int) $per;

					if ($per > 10) {
						$hards[$value] = "block: $key; exectime: $value";
					}
					if ($per > 2) {
						$color = "<font color=red>";
						$colorClose = "</font>";
					} else {
						$color = "";
						$colorClose = "";
					}
					$timing .= "$color<br>block: $key; exectime: $value|| $per% $colorClose Y $mem\n";
				}
			}
		}
	}

	function logStats()
	{
		if (isset($_SERVER['HTTP_REFERER']))
			$data['referer'] = $_SERVER['HTTP_REFERER'];
		else
			$data['referer'] = "N/A";
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$data['remote_address'] = $_SERVER['REMOTE_ADDR'];
	
	}

	function logExecTime()
	{
		global $taking_from_cache, $hit_per, $writing_to_cache, $c_comment;

		$this->logStats();
		if ($this->SHOW_TIME == 1) {

			$mi = microtime();
			//echo "<br>st:".$mi.":";
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - $this->first_time;
			$this->total_time = $riznycja;
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

			$value = (isset($this->times['hits recording'])) ? $this->times['hits recording'] : "";
			//echo " $value";
			$q = $value * 1000;
			$q1 = $this->total_time * 1000;
			$per = ($q / $q1) * 100;
			$per = (int) $per;
			$hit_per = $per;

			$value = $this->times['session_init'];
			//echo " $value";
			$q = $value * 1000;
			$q1 = $this->total_time * 1000;
			$per = ($q / $q1) * 100;
			$per = (int) $per;
			$ses_per = $per;

			$writing_to_cache = $writing_to_cache * 2;
			$c_comment.= $c_comment . "$ses_per";
			//session minus
			if (isset($_SESSION['log_event']))
				$events = $_SESSION['log_event'];
			$riznycja = $cur_time - $this->first_time - $value;
			if ((LOG_EXEC_TIME) || isset($_GET['push_log'])) {
				$_SESSION['log_exec_sql'] = "insert delayed into tbl_exectimes(e_pagename,e_etime,e_date,e_flag1,e_flag2,e_comment,e_events)  values('" . addSlashes($current_page) . "','" . $riznycja . "','" . $mysql_date . "','" . $taking_from_cache . $writing_to_cache . "','" . $hit_per . "','" . $c_comment . "','nfc_events')";
				execSQL("insert delayed into tbl_exectimes(e_pagename,e_etime,e_date,e_flag1,e_flag2,e_comment,e_events)  values('" . $current_page . "','" . $riznycja . "','" . $mysql_date . "','" . $taking_from_cache . $writing_to_cache . "','" . $hit_per . "','" . $c_comment . "','" . $events . "')");
				//if (isset($_GET['push_log']))
				//  echo "insert delayed into tbl_exectimes(e_pagename,e_etime,e_date,e_flag1,e_flag2,e_comment)  values('".$current_page."','".$riznycja."','".$mysql_date."','".$taking_from_cache.$writing_to_cache."','".$hit_per."','".$c_comment."')";
			}
			if (isset($_GET['time']))
				echo $c_comment;
			//$this->sql_execute();
			//if ($this->sql_err) return(11);
		}
	}

	function show_total_time()
	{
		global $timing;

		if ($this->SHOW_TIME == 1) {
			$mi = microtime();
			//echo "<br>st:".$mi.":";
			list($msec, $sec) = explode(" ", $mi);
			$cur_time = $sec + $msec;
			$riznycja = $cur_time - $this->first_time;
			$this->total_time = $riznycja;
			$timing = "<hr>Total:" . "|exec time:" . $riznycja;
			//to do reimplement
			if (isset($this->times['session_init']))
				$timing .= "<br/>Pure:" . "|exec time:" . ($riznycja - $this->times['session_init']);
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
		$this->show_times();
		return $timing;
	}

}

?>