<div class="container shadow p-3 mb-5 bg-white rounded ">
<div class="row justify-content-between">
<p class="h3">Le <?=$ride['fullDateString'] ?> </p>
    <div class="col-lg-6 col-md-12">
        <div class="row">

            <div class="col-5 ">
                <b><?= $ride['startHour'] ?></b> <i class="bi bi-circle-fill"></i> <b><?= $params['searchParams']['startCityName'] ?></b>
            </div>
            <div class="col-2 text-center">
                <?= $ride['travelTime'] ?>
            </div>
            <div class="col-5 text-end">
                <b><?= $ride['endHour'] ?></b> <i class="bi bi-circle"></i> <b><?= $params['searchParams']['endCityName'] ?></b>
            </div>
        </div>
        <hr class="hr hr-blurry">
    </div>
    <div class="col-lg-3 col-md-12 place text-center">
        <b>Places</b>
        <ul class="list-unstyled d-inline">
            <li class="d-inline"><i class="bi bi-circle-fill text-success"></i><?=$ride['seats']['valide_count'] ?> </li>
            <li class="d-inline"><i class="bi bi-circle-fill text-warning"></i> <?=$ride['seats']['pending_count'] ?></li>
            <li class="d-inline"><i class="bi bi-circle"></i><?=$ride['seats']['remaining_seat'] ?></li>
        </ul>
    </div>
</div>
<div class="row align-items-center justify-content-between">
    <div class="col-8 mb-3 mb-lg-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
        </svg>

        <b><?= $ride['userInfos']['first_name']. " ". $ride['userInfos']['last_name'] ?></b>
    </div>
    <div class="col-4 text-center">
        <?php
        // Définition de la route en fonction de la condition isSearch
        $route = $params['isSearch'] ? ROOT.'/recherche/trajet/'.$ride["id_ride_pk"].'/true' : ROOT.'/trajet/'.$ride["id_ride_pk"];
        ?>

        <a class="text-dark mr-3 btn" href="<?= $route ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>
        </a>
    </div>
</div>
</div>
<br>







