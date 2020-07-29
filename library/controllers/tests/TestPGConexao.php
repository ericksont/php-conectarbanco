<?php 

require_once DBS."PGConexao.php";

class TestPGConexao {

	var $id;
    
	function connection() {

		$db = new PGConexao();
		print "Teste Conexão! \n";
		$return = $db->connect();
		var_dump($return);
		
	}

	function create() {

		$db = new PGConexao();
		print "<br/>Teste Insert! \n";
		print "Sucesso! \n";
		$return = $db->create("tb_conexaophp",array("nome"=>"Novo Registro"));
		$this->id = json_decode($return);
		$this->id = $this->id->data;
		var_dump($return);
		print "Erro! Campos vazios \n";
		$return = $db->create("",array());
		var_dump($return);
		print "Insert Duplicado! \n";
		$return = $db->create("tb_conexaophp",array("nome"=>"Novo Registro"));
		var_dump($return);
		print "Dados errados! \n";
		$return = $db->create("tb_conexaophp_nao_existe",array("nome_nao_existente"=>"Novo Registro"));
		var_dump($return);
		
		
	}

	function read() {

		$db = new PGConexao();
		print "<br/>Teste Select! \n";
		$return = $db->read("SELECT * FROM tb_conexaophp WHERE nome ILIKE :nome ;",array("nome"=>"%novo%"));
		var_dump($return);
		print "Sem consulta! \n";
		$return = $db->read("");
		var_dump($return);
		
	}

	function update() {

		$db = new PGConexao();
		print "<br/>Teste Update! \n";
		print "Sucesso! \n";
		$return = $db->update("tb_conexaophp",array("nome"=>"Registro Editado"),array("id"=>$this->id));
		var_dump($return);
		print "Parâmentros vazios! \n";
		$return = $db->update("",array());
		var_dump($return);
		print "Campos errados! \n";
		$return = $db->update("tb_conexaophp",array("nome"=>"Registro Editado"),array("id"=>9999999999));
		var_dump($return);
		
	}

	function delete() {

		$db = new PGConexao();
		
		print "<br/>Erro Delete Vazio! \n";
		$return = $db->delete("",array());
		var_dump($return);
		print "Erro Delete Inexistente! \n";
		$return = $db->delete("tb_conexaophp",array("id = "=>9999999999));
		var_dump($return);
		print "Suscesso Delete! \n";
		$return = $db->delete("tb_conexaophp",array("id = "=>$this->id));
		var_dump($return);
		
	}
	
}

?>