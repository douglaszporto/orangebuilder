<?php 

namespace OrangeBuild;

use \Exception as Exception;

class DB{

	static  $instance     = NULL;
	private $connection   = NULL;
	private $result       = NULL;
	private $errors       = array();
	private $cacheContent = null;
	private $cachePointer = 0;
	private $performCache = true;

	public function __construct() {
		try {
			switch(DB_DRIVER) {

				case 'sqlsrv' :
					$connectionInfo = array(
						'Database' => DB_DATABASE, 
						'UID'      => DB_USER,
						'PWD'      => DB_PASS
					);
					$this->connection = \sqlsrv_connect(DB_HOST,$connectionInfo);

					if(!$this->connection)
						throw new Exception("Não foi possível conectar ao banco de dados: ".print_r(sqlsrv_errors(),true));
					break;
			
				case 'mssql':
					$this->connection = \mssql_connect(DB_HOST, DB_USER, DB_PASS);
					mssql_select_db(DB_DATABASE,$this->connection);
					if(!$this->connection)
						throw new Exception("Não foi possível conectar ao banco de dados");
					break;

				case 'postgres':
					$this->connection = \pg_connect("host=".DB_HOST." port=5432 dbname=".DB_DATABASE." user=".DB_USER." password=".DB_PASS." options='--client_encoding=UTF8'");
					if(!$this->connection)
						throw new Exception("Não foi possível conectar ao banco de dados");
					break;

				case 'mysql_pdo':
					$this->connection = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASS, array(\PDO::ATTR_EMULATE_PREPARES => false));
					if(!$this->connection)
						throw new Exception("Não foi possível conectar ao banco de dados");
					break;

				case 'sqlite_pdo':
					$this->connection = new \PDO('sqlite:'.DB_FILE);
					var_dump($this->connection);
					if(!$this->connection)
						throw new Exception("Não foi possível conectar ao banco de dados");
					break;

				default:
					throw new Exception("Nenhum DBMS definido...");
					break;

			}
		}catch(Exception $e){
			$this->errors[] = $e->getMessage();
		}
	}


	public static function getInstance() {
		if (self::$instance == NULL)
			self::$instance = new DB();
		return self::$instance;
	}


	public function freeResult() {
        if($this->result === null || $this->result === false)
            return true;

		switch(DB_DRIVER) {
			case 'sqlsrv':
				return sqlsrv_cancel($this->result);
			case 'mssql' :
				return mssql_free_result($this->result); 
			case 'postgres':
				return pg_free_result($this->result);
			case 'mysql_pdo':
			case 'sqlite_pdo':
				return $this->result->closeCursor();
		}
	}


	static public function Clean($val){
		switch(DB_DRIVER) {
			case 'sqlsrv':
			case 'mssql' :
                if(is_null($val))
                    return "NULL";
				return '"'.str_replace("'","''",$val).'"'; 
			case 'postgres':
			case 'mysql_pdo':
			case 'sqlite_pdo':
                if(is_null($val))
                    return "NULL";
				return "'".addslashes($val)."'"; 
		}

		return str_replace("'","''",addslashes($val)); 
	}


	public function InsertedId(){
		switch(DB_DRIVER) {
			case 'sqlsrv':
			case 'mssql' :
				$this->Query("SELECT SCOPE_IDENTITY() AS INSERTED_ID");
				$result = $this->Fetch();
				return isset($result["INSERTED_ID"]) ? $result["INSERTED_ID"] : false;
			case 'postgres':
				$this->Query("SELECT lastval() AS INSERTED_ID");
				$result = $this->Fetch();
				return isset($result["INSERTED_ID"]) ? $result["INSERTED_ID"] : false;
			case 'mysql_pdo':
			case 'sqlite_pdo':
				return $this->connection->lastInsertId();
		}

		return false;
	}
	
		 
	public function Query($sql) {
		try {
			switch(DB_DRIVER) {
				case 'sqlsrv':
					$this->result = sqlsrv_query($this->connection, $sql);
					break;
				case 'mssql':
					$this->result = mssql_query($sql,$this->connection);
					break;
				case 'postgres':
					$this->result = pg_query($this->connection,$sql);
					break;
				case 'mysql_pdo':
				case 'sqlite_pdo':
					$this->result = $this->connection->query($sql);
					break;
			}
			if(!$this->result){
                $error = '';
				switch(DB_DRIVER) {
					case 'sqlsrv':
						if(($errors = sqlsrv_errors()) != null)
							foreach($errors as $e)
								$error .= "[".$e[ 'message']."]";
                        break;
                    case 'mysql_pdo':
                    case 'sqlite_pdo':
                        $error = implode(",", $this->connection->errorInfo());
                        break;
				}
                throw new Exception($error, 1);
			}
		} catch(Exception $e) {
            if(!SHOP_PRODUCTION){
                $log = fopen(dirname(__FILE__) . "/../logs/queries.log", 'a');
                fwrite($log, "[".date('Y-m-d H:i:s')."][".$_SERVER['REMOTE_ADDR']."][ERROR]:".preg_replace('~([\s]{2,}|\t|\r\n|\n)~'," ",$sql)." @ ".$e->getMessage()."\n");
			    fclose($log);
            }
			throw new Exception("Erro ao executar consulta");
		}

		return true;
	}


/* 
    TODO: Rever alguma forma de implementar Query Caching

	public function CachedSelect($name, $sql) {

        $this->cacheContent = null;
        $cacheID            = dirname(__FILE__) . "/../cache/". md5($sql) . "." . $sqlParts["TABLE"] . "." . date('Ymd'). ".cache";
        if(file_exists($cacheID)){
            Log::Dump("[".date('Y-m-d H:i:s')."][".$_SERVER['REMOTE_ADDR']."][CACHE]:".preg_replace('~([\s]{2,}|\t|\r\n|\n)~'," ",$sql),"queries.log");
            $this->cacheContent = json_decode(file_get_contents($cacheID));
            $this->cachePointer = 0;
            return true;
        }

			// Cria o arquivo de cache, caso não exista.
			$this->cacheFile    = fopen($cacheID,"x");

			$queryResult = false;
			if(!empty($sql)){
				$queryResult = $this->Query($sql);

				if($queryResult){
					$cacheResult = array();
					while($ret = $this->Fetch())
						$cacheResult[] = $ret;

					file_put_contents($cacheID,json_encode($cacheResult));
					$this->cacheContent = $cacheResult;
				}

				return $queryResult;
			}
		}else{
			// Se caiu aqui, é porque o cache foi desativado nesta instância. Portante, reativo ele.
			// Se a model que o desativou for executada novamente, vou cair aqui mais uma vez e o processo se repetira.
			$this->performCache = true;
			if(!empty($sql))
				return $this->Query($sql);
		}


		return true;
	}
*/

	public function QueryInsert($table, array $fieldsList) {

		$sql = "";
		if(empty($table))
			throw new Exception("Tabela não definida para query de insert");

        $fields = array_keys($fieldsList);
        $values = array_map(array($this,"Clean"), array_values($fieldsList));

        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, implode(',',$fields), implode(',',$values));

		if(!empty($sql))
			return $this->Query($sql);
		
		return false;
	}


	public function QueryUpdate($table, $fieldsList, $filter) {

		$sql = "";
		if(empty($table))
			throw new Exception("Tabela não definida para query de update");

		$values = $fieldsList;
		array_walk($values, function(&$v,$k){
			$v = "{$k} = ".$this->Clean($v);
		});

		$sql = sprintf('UPDATE %s SET %s WHERE %s', $table, implode(',',$values),$filter);
		if(!empty($sql))
			return $this->Query($sql);
		
		return false;
	}


	public function QueryDelete($table, $filter) {
		
        $sql = "";
		if(empty($table))
			throw new Exception("Tabela não definida para query de delete");

		$sql = sprintf('DELETE FROM %s WHERE %s', $table, $filter);
		if(!empty($sql))
			return $this->Query($sql);
		
		return false;
	}

		 
	public function NumRows() {
		switch(DB_DRIVER) {
			case 'sqlsrv':
				return sqlsrv_num_rows($this->result);
			case 'mssql':
				return mssql_num_rows($this->result); 
			case 'postgres':
				return pg_num_rows($this->result);
			case 'mysql':
				return mysql_num_rows($this->result);
			case 'mysql_pdo':
			case 'sqlite_pdo':
				return -1;
		}
	}


	public function Fetch($type = 'assoc') {
		if($this->cacheContent === null)
			return $this->FetchData($type);

		if(!isset($this->cacheContent[$this->cachePointer]))
			return false;

		return $type == 'assoc' ? (array)($this->cacheContent[$this->cachePointer++]) : (object)($this->cacheContent[$this->cachePointer++]);
	}

	
	public function FetchData($type = 'assoc') {
		switch(DB_DRIVER) {
			case 'sqlsrv':
				if($type == "object")
					$result = sqlsrv_fetch_object($this->result);
				else if($type == "assoc"){
					$result = sqlsrv_fetch_array($this->result, SQLSRV_FETCH_ASSOC);
					$error = print_r(sqlsrv_errors(),true);
					if(!empty($error))
						throw new Exception($error);
				}else
					$result = sqlsrv_fetch_array($this->result);
				break;

			case 'mssql':
				if($type == "object")
					$result = mssql_fetch_object($this->result);
				else if($type == "assoc")
					$result = mssql_fetch_assoc($this->result);
				else
					$result = mssql_fetch_array($this->result);
				break;

			case 'postgres':
				if($type == "object")
					$result = pg_fetch_object($this->result);
				else if($type == "assoc")
					$result = pg_fetch_array($this->result, PGSQL_ASSOC);
				else
					$result = pg_fetch_array($this->result);
				break;

			case 'mysql_pdo':
			case 'sqlite_pdo':
				if($type == "object")
					$result = $this->result->fetch(\PDO::FETCH_OBJ);
				else if($type == "assoc")
					$result = $this->result->fetch(\PDO::FETCH_ASSOC);
				else
					$result = $this->result->fetch();
				break;

		}

		return $result === false ? array() : $result;
	}


	static public function GetValues($table, $fieldVal, $fieldDesc, $where = "", $order = "") {

		$result = array();
		$db = DB::getInstance();

		$query = "SELECT {$fieldVal} as 'val', {$fieldDesc} as 'desc' FROM {$table}";
		if($where !== "")
			$query .= "WHERE {$where}";
		if($order !== "")
			$query .= "ORDER BY {$order}";

		$db->Query($query);
		while($row = $db->Fetch("object"))
			$result[$row->val] = $row->desc;

		return $result;
	}
	
    
	public function BeginTransaction() {
		switch(DB_DRIVER) {
			case 'sqlsrv':
				sqlsrv_begin_transaction($this->connection);
				break;
			case 'mssql':
				mssql_query("BEGIN TRANSACTION;",$this->connection);
				break;
			case 'postgres':
				pg_query($this->connection,"BEGIN TRANSACTION");
				break;
			case 'mysql_pdo':
				$this->connection->beginTransaction();
				break;
			case 'sqlite_pdo':
				return;
				break;
		}
	}

	
    public function CommitTransaction() {
		switch(DB_DRIVER) {
			case 'sqlsrv':
				sqlsrv_commit($this->connection);
				break;
			case 'mssql':
				mssql_query("COMMIT TRANSACTION;",$this->connection);
				break;
			case 'postgres':
				pg_query($this->connection,"COMMIT TRANSACTION");
				break;
			case 'mysql_pdo':
				$this->connection->commit();
				break;
			case 'sqlite_pdo':
				return;
				break;
		}
	}


	public function RollbackTransaction() {
		switch(DB_DRIVER) {
			case 'sqlsrv':
				sqlsrv_rollback($this->connection);
				break;
			case 'mssql':
				mssql_query("ROLLBACK TRANSACTION;");
				break;
			case 'postgres':
				pg_query($this->connection,"ROLLBACK TRANSACTION");
				break;
			case 'mysql':
				mysql_query("ROLLBACK",$this->connection);
				break;
			case 'mysql_pdo':
				$this->connection->rollBack();
				break;
			case 'sqlite_pdo':
				return;
				break;
		}
	}


	function __destruct() {
        $this->freeResult();
		switch(DB_DRIVER) {
			case 'sqlsrv':
				sqlsrv_close($this->connection);
				break;
			case 'mssql':
				mssql_close($this->connection);
				break;
			case 'postgres':
				pg_close($this->connection);
				break;
			case 'mysql':
				mysql_close($this->connection);
				break;
			case 'mysql_pdo':
				$this->connection = null;
				break;
		}
	}

};

?>