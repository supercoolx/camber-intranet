<?php
/**
 * Created by PhpStorm.
 * User: Mak
 * Date: 10.10.2018
 * Time: 20:44
 */


class Request
{
    public static function requestHandler()
    {
        $request = $_REQUEST;
        // delete pass
        unset($request['password']);
//TODO we renames errors.php to index.php, do we use this code if yes, fix it
        if (isset($_SESSION['userid']) AND strripos($_SERVER['REQUEST_URI'], 'errors.php') === false) {
            $userData = new stdClass();
            $sql = "
                SELECT
                  userid,
                  username,
                  email_address,
                  userlevel
                FROM users
                WHERE userid = '" . DB::escape($_SESSION['userid']) . "'";

            $row = Db::getRow($sql);

            foreach ($row AS $key => $val)
                $userData->$key = stripslashes($val);

            $userRolesData = '';
            $sql = "SELECT users_roles.name, users_roles.type 
                    FROM users_to_roles, users_roles 
                    WHERE users_to_roles.user_id='" . DB::escape($userData->userid) . "' AND users_to_roles.admin_privileges_id != 0 AND users_to_roles.role_id = users_roles.id";
            $row = Db::getAll($sql);

            foreach ($row AS $val)
                $userRolesData .= stripslashes($val['type']) ." / ". stripslashes($val['name']) . "; ";

            $requestData = new stdClass();

            $requestData->request = var_export($request, true);
            $requestData->method = $_SERVER['REQUEST_METHOD'] . ': ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $requestData->date = time();
            $requestData->userName = $userData->username;
            $requestData->userId = $userData->userid;
            $requestData->userRoles = $userRolesData;
            $requestData->emailAddress = $userData->email_address;

            self::writeRequestLogDb($requestData);
        }
    }

    static function writeRequestLogDb($requestData, $filename = 'error.logs')
    {
        //$filename = ERROR_LOG_PATH . $filename;
/*        
        if (!is_file($filename)){
            $sqlite = new SQLite3($filename);
            if(!$sqlite) { return; }
            $sql = 'PRAGMA encoding = "UTF-8";
                CREATE TABLE requestentrys (
                    id            INTEGER PRIMARY KEY,
                    requestentry  TEXT,
                    method        CHAR,
                    date          INT,
                    user_name     CHAR,
                    user_id       INT,
                    user_roles    CHAR,
                    email_address CHAR
                );';
            $sqlite->exec($sql);
        } else {
            $sqlite = new SQLite3($filename);
            if (!$sqlite) { return; }
        }
	
        if (!self::isTable($sqlite))
        {
            $sql = 'PRAGMA encoding = "UTF-8";
                CREATE TABLE requestentrys (
                    id            INTEGER PRIMARY KEY,
                    requestentry  TEXT,
                    method        CHAR,
                    date          INT,
                    user_name     CHAR,
                    user_id       INT,
                    user_roles    CHAR,
                    email_address CHAR
                );';
            $sqlite->exec($sql);
        }
 * 
 */
//        $sqlite = new SQLitePDO($filename);
//        global $LogsDBTables;
//        $sqlite->init($LogsDBTables);
         
//        if (filesize($filename) > 1048576 * 300){
//            self::dbSize($sqlite);
//        }
//        $sqlite->busyTimeout(5000);
//	$sqlite->exec("PRAGMA journal_mode=WAL;");

        $request = array();
        foreach((array)$requestData->request as $key=>$r) {
            $r = str_replace('"', "_", $r);
            $request[$key] = $r;
        }
//        try{
////        $sqlite->exec('BEGIN IMMEDIATE;');//$r=$sqlite->query('PRAGMA journal_mode');var_dump($r->fetchArray());exit;
//        $sql = 'INSERT INTO requestentrys (requestentry, method, date, user_name, user_id, user_roles, email_address)  
//          VALUES("'.$request[0].'","'.$requestData->method.'","'.$requestData->date.'","'.$requestData->userName.'","'.$requestData->userId.'","'.$requestData->userRoles.'","'.$requestData->emailAddress.'")';
//        $sqlite->exec($sql);
//        }
//        catch(Exception $e){
//            
//        }
        Logging::requestsWrite($request, $requestData);
    }

    static function isTable($sqlite)
    {
        $result = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name='requestentrys'");
        $res = $result->fetchArray();
        if($res['name'] != 'requestentrys')
            return false;
        return true;
    }

    static function dbSize($sqlite)
    {
        $result = $sqlite->exec("DELETE FROM requestentrys where id in (select id from requestentrys order by id Asc LIMIT 100)");        
    }
}
