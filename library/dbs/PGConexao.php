<?php 
/*
 * -- V 1.0.0
 */
require_once DBS."PostgreSQL.php";

class PGConexao extends PostgreSQL {
    
	function __construct() {
		
		if(ENVIRONMENT == "localhost") {
			$this->host = "localhost";
			$this->port = "5432";
			$this->dbname = "conexaophphomolog";
			$this->user = "conexaophphomolog";
			$this->pass = "secret";
		} else if(ENVIRONMENT == "homologation") {
			$this->host = "pgsql.conexaophphomolog.com";
			$this->port = "5432";
			$this->dbname = "conexaophphomolog";
			$this->user = "conexaophphomolog";
			$this->pass = "secret";
		} else if(ENVIRONMENT == "production"){
			$this->host = "pgsql.conexaophp.com";
			$this->port = "5432";
			$this->dbname = "conexaophp";
			$this->user = "conexaophp";
			$this->pass = "secret";
		} else {
            $util = new Util();
			return $util->jsonReturn(3,"Alerta! \n Informe o ambiente."); 
        }
		
	}
	
}

?>