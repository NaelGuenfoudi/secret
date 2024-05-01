<?php include("header.php"); ?>




<div class="container text-center ">
<h1 class="title text-center h4">Le <?= $params["rideInfos"]['title'] ?> </h1>
<br>
<?php if ($params['search']): ?>
    <div class="text-center">
        <a href="<?= ROOT . '/rechercher' ?>" class="btn bg-black text-warning">Retour à la recherche</a>
    </div>
<?php endif; ?>

<div class="row justify-content-between">

    <div class="col-5 text-start">
        <b><?= $params["rideInfos"]['d_start_hour']  ?></b> <i class="bi bi-circle-fill"></i> <b><?= $params["rideInfos"]['start_city_name'] ?></b>
    </div>
    <div class="col-2 text-center">
        <?= $params["rideInfos"]['travel_time'] ?>
    </div>
    <div class="col-5 text-end">
        <b><?= $params["rideInfos"]['d_end_hour'] ?></b> <i class="bi bi-circle"></i> <b><?= $params["rideInfos"]['end_city_name']  ?></b>
    </div>
</div>
<br>
<div class="row">
<div class=" col-md-12 place text-center">
<b>Places</b><br>
<ul class="list-unstyled d-inline">
    <li class="d-inline"><i class="bi bi-circle-fill text-success"></i><?=$params["rideInfos"]['seats']['valide_count'] ?></li>
    <li class="d-inline"><i class="bi bi-circle-fill text-warning"></i> <?=$params["rideInfos"]['seats']['pending_count'] ?></li>
    <li class="d-inline"><i class="bi bi-circle"></i><?=$params["rideInfos"]['seats']['remaining_seat'] ?></li>
</ul>
</div>
</div>

    <hr class="hr hr-blurry">
    <p><?=$params["rideInfos"]['desc_message'] ?></P>
    <hr class="hr hr-blurry">

    <div class="row ">
<div class="col ">
<img src="<?=ROOT . '/ressources/uploads/profil/' .$params["rideInfos"]['profil_image']?>"class="rounded-circle profil-img">
<p class="h5"><?= $params["rideInfos"]['first_name']. ' '.$params["rideInfos"]['last_name']?></p>
</div>
</div>

<div class="row ">
<div class="col ">
<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16">
<path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"/>
</svg>
<p class="h5"><?= $params["rideInfos"]['vehicle_name'] ?></p>
<p ><?= $params["rideInfos"]['vehicle_color'] ?></p>
</div>
</div>


<?php
switch ($params['accessLevel']) {
    case 0:
        include('Components/booking_form_template.php');
        break;
    case 1:
        switch ($params['userBooking']['status']) {

            case "En attente":
                include('Components/booking_form_template.php');
                break;
            case "Refusée":
                echo '<p class="alert alert-danger">Votre réservation sur ce trajet a été refusée</p>';
                break;
        }
        break;
    case 2:
        include('Components/booking_detail.php');
        include('Components/message_form.php');
        break;
    case 3:
        include('Components/booking_detail.php');
        include('Components/message_form.php');
        break;
}
?>



<br>
