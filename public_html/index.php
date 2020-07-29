<?php
    print "<H1> Conexão PHP </H1>";
    print "<pre>";

    require_once "conf.php";

    require_once CONTROLLERS."tests".BAR."TestPGConexao.php";

    $obj = new TestPGConexao();
    $obj->connection();
    $obj->create();
    $obj->read();
    $obj->update();
    $obj->delete();
?>