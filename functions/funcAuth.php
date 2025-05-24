<?php
    function clean($val) {
        return trim(htmlspecialchars($val));
    }
    function validRequest($val){
        if(trim($val) == '')
            return false;
        else
            return true;
    }
    function validEmail($val){
        $valueclean = filter_var($val, FILTER_SANITIZE_EMAIL); 

        if ($valueclean!=$val)
            return false;
        else if(filter_var($val, FILTER_VALIDATE_EMAIL) === FALSE)
            return false;
        else
            return true;
    }
    function validName($valor) {
        if (preg_match("/^[a-zA-Z]+$/", $valor))
            return true;
        return false;
    }
    function validLength($val, $min=0, $max=0) {
        if ($min>0 && strlen($val) < $min) 
            return false;
        else if ($max>0 && strlen($val) > $max) 
            return false;
        else
            return true;
    }
?>