<div class="container-fluid shadow p-3 mb-5 bg-white rounded">
    <div class="row ">
        <h2 class='h3'>Trajet du <?= $request['fullDateString'] ?></h2>
        <div class="col-lg-6 col-md-6 col-sm-12 ">


            <b><i class="bi bi-circle-fill"></i> <?= $request['start_city_name']; ?></b>
            <p class="no-margin"><?= $request['travel_time']; ?></p>
            <b><i class="bi bi-circle"></i> <?= $request['end_city_name']; ?></b>


        </div>


        <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
            <a href="<?= ROOT ?>/reservations/traiter/0/<?= $request['id_booking_pk'] ?>" class="text-dark mr-3 btn"><i
                    class="bi bi-x-circle-fill h3 text-danger"></i></a>
            <a href="<?= ROOT ?>/reservations/traiter/1/<?= $request['id_booking_pk'] ?>" class="text-dark btn"><i
                    class="bi bi-check-circle-fill h3 text-success"></i></a>
        </div>
    </div>
    <hr class="hr hr-blurry">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <b><?= $request['first_name'] . ' ' . $request['last_name'] ?></b>
        </div>
        <div class="col-6 text-end">
            <b><?= $request['seat_number'] ?> <i class="bi bi-people-fill h3"></i></b>
        </div>
        <button id="consultDetail<?= $request['id_booking_pk'] ?>" class="text-dark btn ">Voir les détails de la
            réservation <i class="bi bi-info-circle-fill"></i> </button>
    </div>
    <div class="row justify-content-center hidden" id="detailSection<?= $request['id_booking_pk'] ?>">
        <div class="col-6 text-center">
            <img src="<?= ROOT . '/ressources/uploads/profil/' . $request['profil_image'] ?>" alt="Image de profil"
                class="rounded-circle miniature-img"></img>
        </div>
        <div class="row align-items-center justify-content-between text-center">
            <div class="col-lg-4">
                <p><i class="bi bi-geo-alt-fill"></i> <?= $request['address'] . ' ' . $request['user_city'] ?>
            </div>
            <div class="col-lg-4">
                <p><i class="bi bi-envelope"></i> <?= $request['mail'] ?>
            </div>
            <div class="col-lg-4">
                <p><i class="bi bi-telephone-fill"></i> <?= $request['phone'] ?>
            </div>
        </div>
    </div>

</div>





<script>
    //affiche/cache les details sur une demande de réservation. Astuce pour pour cibler de manière unique chaque réservation. On concatene dans l'id des éléments l'id de reservation
    document.getElementById('consultDetail<?= $request['id_booking_pk'] ?>').addEventListener('click', function () {
        document.getElementById('detailSection<?= $request['id_booking_pk'] ?>').classList.toggle('hidden');
    });

</script>