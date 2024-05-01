<div class="container">
    <h1 class="text-center">Mes véhicules</h1>
    
    
    <?php 
    foreach($params['vehicleList'] as $vehicle){
        include('components/vehicle.php');
    }
    
    
    ?>
            <div class="row">
            <div class="col-12 text-center">
            <a href="<?=ROOT ?>/mon_espace/vehicule/ajouter" class="text-dark btn">
                <i class="bi bi-plus-circle-fill h1"></i>
            </a>
        </div>
</div>

</div>