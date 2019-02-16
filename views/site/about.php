<?php
$this->params['breadcrumbs'][] = ['label' => "Websocket test", 'template' => "<span>{link}</span>\n"];
?>
<script src="autobahn.min.js"></script>
<div class="wrapper">
<div class="box">
<h1 class="text-center" style="width: 8rem;">О нас</h1>    
</div>


<!-- websocket  -->
<div class="box">
 <h2 class="text-center" style="width: 8rem; ">Websocket message</h2>

<hr>
       <div class="box">
         <div style="float: left;">Param1: </div>
        <div style="float: left;" id="sum_leads"></div>  
       </div>
       
       <div class="box">
         <div style="float: left;">Param2: </div>
        <div id="count_leads"></div>  
       </div>
<hr>   
</div>
</div>



<?php

$this->registerJsFile ("@web/js/ws.js");

?>