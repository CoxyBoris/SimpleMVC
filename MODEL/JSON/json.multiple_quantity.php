<?php
/*
$get_id_model = filter_input(INPUT_GET,'id_model',FILTER_VALIDATE_INT);

if(isset($get_id_model) && $get_id_model>0 )
{
    require_once '../../INCLUDE/include_ajax.php';
    $mysql = new Connection();
    $conn = $mysql->getConnexion();

    $JSON = array();
    
    $Omodel = new Cmodel($get_id_model);
    $Osize_grid = new Csize_grid($Omodel->get_m_id_size_grid());
    $arr_Osize_X = $Osize_grid->get_Osize();
    $arr_Osize_Y = $Osize_grid->get_Osize_Y();
    if($Osize_grid->get_m_b_matrix())
    {
        $arr_id_sizes = $Omodel->get_arr_id_sizes(true);
        foreach($arr_Osize_Y as $key_Y => $Osize_Y)
        {
            $JSON_X = array();
            foreach($arr_Osize_X as $key_X => $Osize_X)
            {
                $b_active = false;
                if( in_array(array($Osize_X->get_m_id(), $Osize_Y->get_m_id()), $arr_id_sizes) )
                {
                    $b_active = true;
                }
                $JSON_X[] = array("X" => $Osize_X->get_m_order(), "Y" => $Osize_Y->get_m_order(), "ACTIVE" => $b_active, "VALUE" => 0);
            }

            $JSON[] = $JSON_X;
        }
    }
    else
    {
        $arr_id_sizes = $Omodel->get_arr_id_sizes();
        foreach($arr_Osize_X as $key_X => $Osize_X)
        {
            $b_active = false;
            if( in_array($Osize_X->get_m_id(), $arr_id_sizes) )
            {
                $b_active = true;
            }
            $JSON[] = array("X" => $Osize_X->get_m_order(), "ACTIVE" => $b_active, "VALUE" => 0);
        }
    }
    
    ?>
    JSON_multiple_quantity = <?=json_encode($JSON)?>;
    <?

    $mysql = null;
    $conn = null;
}
    
    /*$arr_supplies = $Omodel->get_supplies_list();  
    
    $JSON = array();
    
    foreach($arr_supplies as $id_color => $supplies)
    {
        $JSON[$id_color] = array();
        foreach($supplies as )
    }
    
    $JSON = array(
        "ID_COLOR" => "id"
    );*/
