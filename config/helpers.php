<?php 

function dep($data)
    {
        $format  = print_r('<pre>');
        $format .= print_r($data);
        $format .= print_r('</pre>');
        return $format;
    }


    function media()
    {
        return BASE_URL."/assets";
    }

    function base_url(){
        return BASE_URL;
    }

    function headerAdmin()
    {
        $view_header = "../../template/headerAdmin.php";
        require_once ($view_header);
    }
    
    function footerAdmin()
    {
        $view_footer = "../../template/footer.php";
        require_once ($view_footer);        
    }

    function navAdmin()
    {
        $view_nav = "../../template/navAdmin.php";
        require_once ($view_nav);        
    }

    function headerCajero()
    {
        $view_header = "../template/header.php";
        require_once ($view_header);
    }
    
    function footerCajero()
    {
        $view_footer = "../template/footer.php";
        require_once ($view_footer);        
    }

    function navCajero()
    {
        $view_nav = "../template/nav.php";
        require_once ($view_nav);        
    }
   

?>

