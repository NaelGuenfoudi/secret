<div class="container">
    <h1 class="text-center">Demande de réservation</h1>


    <?php

    if(isset($params['msg'])){
        if($params['msg']['type'] == "error"){
            echo "<p class='alert alert-danger'>".$params['msg']['message']. "</p>";
        }
        if($params['msg']['type'] == "success"){
            echo "<p class='alert alert-success'>".$params['msg']['message']. "</p>";
        }
    }


    if($params['requestList']){
        foreach ($params['requestList'] as $request) {
            include ('components/request.php');
        }
    
    }
    else{
        echo "<p class='text-center'>Vous n'avez pas de demande de réservation en attente. </p>";
    }


    ?>


</div>