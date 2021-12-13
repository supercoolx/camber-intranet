<?php

class SQLitePDO extends PDO 
{
    public function __construct($filename) 
    {
        parent::__construct('sqlite:' . $filename,null,null,array(PDO::ATTR_PERSISTENT => true));
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        parent::exec('PRAGMA busy_timeout=5000;PRAGMA synchronous=NORMAL;PRAGMA journal_mode=WAL;');		
    }
	
	public function busyTimeout($microsec)
    {
        $sql = 'PRAGMA busy_timeout='.$microsec.';';
        parent::exec($sql);
	}
	
	public function selectAll($sql)
    {            
        $statement = parent::query($sql);
        $result = $statement->fetchAll();            
        $statement = null;
        return $result;
    }
	
	public function select($sql)
    {            
        $statement = parent::query($sql);
        $result = $statement->fetch();            
        $statement = null;
        return $result;
    }
	
	public function selectColumn($sql)
    {            
        $statement = parent::query($sql);
        $result = $statement->fetchColumn();            
        $statement = null;
        return $result;
    }
	
	public function exec($sql)
    {
        parent::beginTransaction();
        parent::exec($sql);
        parent::commit();
	}
        
    public function query($sql)
    {            
        parent::beginTransaction();
        $result = parent::query($sql);
        parent::commit();
        return $result;
	}

    public function beginTransaction() 
    {
        return parent::beginTransaction();
    }

    public function commit() 
    {
       return parent::commit();        
    }

    public function rollBack() 
    {
        return parent::rollBack();
    }
        
    public function insert($table, $data)
	{
        $field_list = '';
        $value_list = '';
        $values = array();

        foreach ($data as $k => $v) {
            $field_list .= "`".$k."`" . ',';
            $value_list .= "?" . ',';
            $values[] = $v;
        }

        $field_list = rtrim($field_list,',');
        $value_list = rtrim($value_list,',');

        $sql = "INSERT INTO `{$table}` ({$field_list}) VALUES ($value_list)";
        $st = parent::prepare($sql);
        if ($st->execute($values)) {
            return parent::lastInsertId();
        } else {
            return false;
        }		
	}
        
    public function update($table, $data, $id)
	{
        $values = array();
        $sql = "UPDATE ".$table." SET ";
        foreach($data as $k => $v){
            $sql .= "`".$k."`=? ";
            $values[] = $v;
        }
        $sql .= "WHERE id = ".$id;
        $st = parent::prepare($sql);
        return $st->execute($values);
	}
	
	public function getTables()
	{
		$tablesquery = parent::query("SELECT name FROM sqlite_master WHERE type='table'");
		return $tablesquery->fetchAll();
	}
	
	public function getFields($table)
	{
		$res = parent::query("PRAGMA table_info({$table})");
		return $res->fetchAll();
	}
	
	public function close(){}
}

?>