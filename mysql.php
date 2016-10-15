<?php
class Mysql {
    var $handler;
    private static $db_instance;
    public static function getInstance()
    {
        if (Mysql::$db_instance === NULL)
        {
        	Mysql::$db_instance = new Mysql();
      	}
      	return Mysql::$db_instance;
    }    
    public function __construct() {
       if (!DB_PORT)
        $this->handler = mysql_connect(DB_HOST, DB_USER,DB_PASS);
       else
        $this->handler = mysql_connect(DB_HOST.':'.DB_PORT, DB_USER,DB_PASS);
       if (!$this->handler) {
            throw new RestException(501, 'MySQL: ' . mysql_error());
       }
       mysql_select_db(DB_DBASE, $this->handler);
       mysql_query("set names utf8mb4");
       
	}

    public function __destruct() {
        mysql_close($this->handler);
    }

    public function rs($fields='*', $table, $params) {
       $start = isset($params['start'])?$params['start']:0;
       $limit = isset($params['limit'])?$params['limit']:300;
       $sort  = isset($params['sort'])?$params['sort']:'updated';
       $dir   = isset($params['dir'])?$params['dir']:'desc';
       $ordercond = is_null($sort) || is_null($dir) ? NULL : "order by {$sort} {$dir}";
       $limitcond = is_null($limit) || is_null($start) ? NULL : "limit {$start}, {$limit}";
        // get conditions
       unset($params["start"]);
       unset($params["limit"]);
       unset($params["sort"]);
       unset($params["dir"]);
       unset($params["_dc"]);
       unset($params["page"]);
       unset($params["group"]);
       $cond=null;
       foreach ($params as $key => $value) {
           $value = mysql_real_escape_string($value);
           $cond[] = " $key = '{$value}'";
       }
       $cond = is_null($cond) ? NULL : " WHERE " . implode(" AND ", $cond);


       // 取得总数
       $sql = "SELECT count(id) FROM " . $table . $cond;
       //var_dump(var_export($sql));
       $result = mysql_query($sql);
       if (!$result) {
            $error['error']=TRUE;
            $error['type']='mysql';
            $error['error_message']='Invalid query: ' . mysql_error();
            //$error['sql']=$sql;
            return $error;
        }
       $row = mysql_fetch_row($result);
       $ret["total"] = $row[0];
       $ret["data"] = array();
       
       

       if ($ret["total"] > 0 && $start < $ret["total"]) {
           $sql = "select {$fields} from {$table} {$cond} {$ordercond} {$limitcond}";
           //var_dump(var_export($sql));
           $result = mysql_query($sql);
           if (!$result) {
                $error['error']=TRUE;
                $error['type']='mysql';
                $error['error_message']='Invalid query: ' . mysql_error();
                //$error['sql']=$sql;
                return $error;
           }
           $fields_num = mysql_num_fields($result);
           while ($row = mysql_fetch_row($result)) {
               $record = array();
               for ($i = 0; $i < $fields_num; $i++) {
                   $record[mysql_field_name($result, $i)] = $row[$i];
               }
               array_push($ret["data"], $record);
           }
       }
       ////$ret["sql"] = $sql;
       $ret['table']=$table;
       return $ret;
    }

    public function insert($table, $fields='*') {
        unset($fields["id"]);
        $values = array_map('mysql_real_escape_string', array_values($fields));
        $sql = sprintf('INSERT INTO %s (%s) VALUES ("%s")', $table, implode(',',array_keys($fields)), implode('","',$values));
        $result = mysql_query($sql);
        if (!$result) {
            $error['error']=TRUE;
            $error['type']='mysql';
            $error['error_message']='Invalid query: ' . mysql_error();
            //$error['sql']=$sql;
            return $error;
        }
        $return['result']=TRUE;
        $return['table']=$table;
        $return['id']=mysql_insert_id();
        return $return;
    }

    public function update($table, $id, $fields='*') {
        foreach ($fields as $key => $value) {
            $value = mysql_real_escape_string($value);
            $updates[] = "$key = '{$value}'";
        }
        $implode = implode(", ", $updates);
        $sql = "UPDATE $table SET $implode WHERE id = '$id'";
        $result = mysql_query($sql);
        if (!$result) {
            $error['error']=TRUE;
            $error['type']='mysql';
            $error['error_message']='Invalid query: ' . mysql_error();
            //$error['sql']=$sql;
            return $error;
        }
        $return['result']=TRUE;
        $return['table']=$table;
        $return['id']=$id;
        return $return;
    }
    public function destroy($table, $id) {
        $sql = "DELETE FROM $table WHERE id = $id";
        $result = mysql_query($sql);
        if (!$result) {
            $error['error']=TRUE;
            $error['type']='mysql';
            $error['error_message']='Invalid query: ' . mysql_error();
            //$error['sql']=$sql;
            return $error;
        }
        $return['result']=TRUE;
        $return['table']=$table;
        $return['id']=$id;
        return $return;
    }
}
?>