<?php 

/*
 * -- V 1.0.0
 */
class PostgreSQL {
    
	private $conn;

	protected $host;
	protected $port;
	protected $dbname;
	protected $user;
	protected $pass;
	
	function connect () {
		try {
			$this->conn = new PDO("pgsql:host=".$this->host."; port=".$this->port."; dbname=".$this->dbname."; user=".$this->user."; password=".$this->pass);
			if(ENVIRONMENT == "locahost" || ENVIRONMENT == "homologation") 
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$util = new Util(); 
				$return = $util->jsonReturn(1,"Sucesso! \n Conexão efetuada com sucesso.");
		} catch (PDOException $e){
			$log = new Log();
			$log->writeTxt($e);
			$util = new Util();
			$return = $util->jsonReturn(2,"Erro no processamento! \n Entre em contato com o suporte e informe a data e hora do erro."); 
		}
		return $return;
	}

	/*
 	 * $table (String)
 	 * $fields (Array)
 	 ** Ex. de Uso $pgsql->create(“nome_da_tabela”,array(“nome_do_campo_01”=>“Nome do Campo 01”, “nome_do_campo_02”=>“Nome do Campo 02”));
 	 */
	function create ($table, $fields) {
		
		$util = new Util();

		if($table == "" || $fields == "" || empty($fields))
			$return = $util->jsonReturn(3,"Parâmetros obrigatórios não preenchidos!"); 
		else if(isset($_SESSION["create_fields"]) && $_SESSION["create_fields"] == $fields)
			$return = $util->jsonReturn(3,"Registro já inserido!");
		else {
			
			$fieldsStr = "";
			foreach ($fields as $key => $val) {
				$fieldsStr .= ":".$key.",";
			}
			$parameters = substr($fieldsStr,0,-1);
			$fieldsStr = str_replace(":","",$parameters);
				
			$sql = "INSERT INTO " .  $table  . " (".$fieldsStr.") VALUES (".$parameters.") returning id;";

			if(SQL == "show"){
				print $sql;
				var_dump($fields);
			}

			try {
				
				$this->connect();
				$rs = $this->conn->prepare($sql);
					
				$rs->execute($fields);
				$return = $rs->fetch(PDO::FETCH_OBJ);
					
				if(!isset($return->id) || $return->id == 0){
					$return = $util->jsonReturn(3,"Erro ao inserir!"); 
				} else {
					$return = $util->jsonReturn(1,"",$return->id); 
				}

				$_SESSION["create_fields"] = $fields;
					
			} catch (PDOException $e){
				$log = new Log();
				$log->sendError($e);
				$return = $util->jsonReturn(2,"Erro no processamento! \n Entre em contato com o suporte e informe a data e hora do erro.");
			}
		}
			
		return $return;
	}
	
	/*
	 * $sql (String)
	 * $values (Array)
 	 ** Ex. de Uso $pgsql->read(“SELECT * FROM nome_da_tabela WHERE nome_do_campo_01 ILIKE :nome_do_campo_01;”, array(“nome_do_campo_01”=>“Nome do Campo 01”))
 	 */
	function read ($sql, $values=array()) {

		$util = new Util();

		if($sql == ""){
			$return = $util->jsonReturn(3,"Erro ao consultar!");
		} else {
			
			$countParam = substr_count($sql,"?");
			
			if(substr_count($sql,":") >= 1)
				$countParam = $countParam + 1;

			if(SQL == "show"){
				print $sql;
				var_dump($values);
			}

			try{
				$this->connect();
				$list = array();
				$rs = $this->conn->prepare($sql);

				if($rs->execute($values)){
					if($rs->rowCount() > 0){
						$i = 0;
						while($row = $rs->fetch(PDO::FETCH_OBJ)){
							$list[$i] = $row;
							$i++;
						}
					}
				}
				$return = $util->jsonReturn(1,"",$list);
			} catch (PDOException $e){
				$log = new Log();
				$log->sendError($e);
				$return = $util->jsonReturn(2,"Erro no processamento! \n Entre em contato com o suporte e informe a data e hora do erro.");
			}
				
		} 
		
		return $return; 
		
	}
	
	/*
 	 * $table (String)
	 * $fields (Array)
	 * $condition (Array) 
 	 ** Ex. de Uso $pgsql->update(“nome_da_tabela”,array(“nome_do_campo_01”=>“Nome do Campo 01”, “nome_do_campo_02”=>“Nome do Campo 02”), array("id"=>1);
 	 */
	function update ($table, $fields, $condition=array()) {
		
		$util = new Util();
		if($table == "" || $fields == "")
			$return = $util->jsonReturn(3,"Parâmetros obrigatórios não preenchidos!");
		else {

			$fieldsStr = "";
			foreach ($fields as $key => $val) {
				$fieldsStr .= " ".$key." = :".$key.",";
			}
			$fieldsStr = substr($fieldsStr,0,-1);

			$conditionsStr = "";
			foreach ($condition as $key => $val) {
				$fields[str_replace(array(" ",">","<","=","IS","NOT"),"", $key)] = $val;
				$conditionsStr .= " ".$key." = :".str_replace(array(" ",">","<","=","IS","NOT"),"", $key).",";
			}
			$conditionsStr = substr($conditionsStr,0,-1);

			$sql = "UPDATE " .  $table  . " SET ". $fieldsStr. " WHERE ". $conditionsStr;
			if(SQL == "show"){
				print $sql;
				var_dump($fields);
			}

			try{
				$this->connect();
				$rs = $this->conn->prepare($sql);
				$rs->execute($fields);

				if($rs->rowCount() == 0){
					$return = $util->jsonReturn(3,"Erro ao atualizar! \n Verifique se o valor já foi cadastrado anteriormente."); 
				} else {
					$return = $util->jsonReturn(1,""); 
				}
			} catch (PDOException $e){
				$log = new Log();
				$log->sendError($e);
				$return = $util->jsonReturn(2,"Erro no processamento! \n Entre em contato com o suporte e informe a data e hora do erro.");
			}
			
		}
			
		return $return;
	}
	

	/*
 	 * $table (String)
	 * $condition (Array) 
 	 ** Ex. de Uso $pgsql->delete(“nome_da_tabela”, array(“id = ”=>1);
 	 */
	function delete ($table, $condition=array()) { 
		
		$util = new Util();
		if($table == "")
			$return = $util->jsonReturn(3,"Parâmetros obrigatórios não preenchidos!");
		else {
			
			$conditionsStr = "";
			$fields = array();
			foreach ($condition as $key => $val) {
				$fields[str_replace(array(" ",">","<","=","IS","NOT"),"", $key)] = $val;
				$conditionsStr .= " ".$key." :".str_replace(array(" ",">","<","=","IS","NOT"),"", $key).",";
			}
			$conditionsStr = substr($conditionsStr,0,-1);

			$sql = "DELETE FROM ".$table." WHERE ".$conditionsStr;
			if(SQL == "show"){
				print $sql;
				var_dump($condition);
			}

			try{

				$this->connect();
				$rs = $this->conn->prepare($sql);
				$rs->execute($fields);
				if($rs->rowCount() == 0)
					$return = $util->jsonReturn(3,"Erro ao remover! \n Verifique se o valor já foi cadastrado anteriormente."); 
				else 
					$return = $util->jsonReturn(1,""); 
				
			} catch (PDOException $e){
				$log = new Log();
				$log->sendError($e);
				$return = $util->jsonReturn(2,"Erro no processamento! \n Entre em contato com o suporte e informe a data e hora do erro.");
			}
			
		}

		return $return;

	}
}

?>