<?php 

require_once 'Log.php';

/*
 * -- V 1.0.0
 */
class Util {
    
    /*
     * $type (Integer) : 1.Sucesso, 2.Erro, 3.Alerta
     * $message (String)
     * $data (Object)
     ** Ex. de uso $util->jsonReturn(2, “Ocorreu um erro na solicitação”, new stdClass());
     */
	function jsonReturn ($type, $message, $data=null){

        if($type == 1)
            $typeDescription = "Sucesso";
        else if($type == 2)
            $typeDescription = "Erro";
        else if($type == 3)
            $typeDescription = "Alerta";

        $obj = new stdClass();
        $obj->type = $type;
        $obj->typeDescription = $typeDescription;
        $obj->message = $message;
        $obj->data = $data;
        
        return json_encode($obj);
		
    }
    
}
?>