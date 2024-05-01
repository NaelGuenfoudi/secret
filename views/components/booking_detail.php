
<div class="row">
    <h2 class="h4">Coordonées du conducteur </h2>
    <div class="col-lg-4">

                <p><i class="bi bi-geo-alt-fill"></i><br> <?= $params['rideInfos']['address'] . ' ' . $params['rideInfos']['user_city'] ?>
            </div>
            <div class="col-lg-4">
                <p><i class="bi bi-envelope"></i> <br><?= $params['rideInfos']['mail'] ?>
            </div>
            <div class="col-lg-4">
                <p><i class="bi bi-telephone-fill"></i><br> <?= $params['rideInfos']['phone'] ?>
            </div>
</div>
<br>
<hr class="hr hr-blurry">
<h2 class="h4">Les passagers</h4>
<hr class="hr hr-blurry">
<div class="row justify-content-center">
<?php 
if(isset($params['bookingList'])){
    foreach($params['bookingList'] as $booking){
        include('booking_item.php');
    }
}
    ?>
    </div>
