<html>
    <head><title></title>
        <script src="view/bower_components/jquery/dist/jquery.min.js"></script>

    </head>
    <body leftmargin=0 topmargin=0 style="padding:0px 0px 0px 0px; margin:0px 0px 0px 0px" id="pageContent">
    <?php

    $isPrint = 2;
    $code = $_GET['code'];
    $quantity = $_GET["qty"];
    $mrp = $_GET["mrp"];
    $text = $code;
    $b=0;        
    for($q=1;$q<=$quantity;$q++) {
        
        $b++;
        $option_width = "49%";
        $option_height = "99%";

        
        if($b==3) {
            $b=1;
          
        } 
        $clear = "";
        $mar_left = "margin-left:30px;";
        if($b==1) {
            $clear = "clear:both;";
            $mar_left = "";
            
           
        } 

                
        echo '<div  style="'.$clear.'width:'.$option_width.'; font-family:verdana; height:'.$option_height.'; font-size:14px; text-align:center; word-wrap: break-word;overflow: hidden; text-overflow: ellipsis;white-space: nowrap;margin-left:0.5% !important;margin-right:0.5%;float:left">
                <div style = "'.$mar_left.'margin-top:7px;">
                    <div style="font-family:verdana; font-size:11px; text-align:center; word-wrap: break-word;margin-top:5px;margin-bottom:5px;float:left;">
                        <div style="margin-bottom:0px;font-size:10px">Lakshmi Market</div>
                        <img style="width:120px;height:28px;" id="barcode_src" src="barcode.php?text='.$text.'"  />
                        <div style>
                            <span style="width:40%;float:left">'.$code.'</span>
                            <span style= "width:40%;float:right;font-size:10px"><span>&#8377</span><span>'.$mrp.'</span>
                        </div>
                    </div>
                </div>
            </div>';
    
        
        }
    ?>

<script>  

    setTimeout(function(){ 
        window.print(); 
        //self.close(); 
    }, 1000);

    


</script>
</body>
</html>







