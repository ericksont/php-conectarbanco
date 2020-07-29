<?php 

require_once DBS."PGConexao.php";

/*
 * -- V 1.0.0
 */
class Log {
    
        /*
         * Arquivo com o log ROOT/logs.txt
         * $txt (String)
         ** Ex. de uso $log->writeTxt(“Mensagem de Erro!”);
         */
        function writeTxt($txt) {
                $message = date("d/m/Y H:i")." ---------------------------------------------- \n";
                $message .= $txt."\n\n\n";

                $fp = fopen(HTML."logs.txt", "a");
                fwrite($fp, $message);
                fclose($fp); 
        }
        
        /*
         * Grava os dados no Banco de Dados
         * $txt (String)
         ** Ex. de uso $log->sendError(“Mensagem de Erro!”);
         */ 
        function sendError($txt){
                $pgDSousa = new PGConexao();
                $pgDSousa->create("logs_errors",array("message"=>$txt));
        }
	
}

?>