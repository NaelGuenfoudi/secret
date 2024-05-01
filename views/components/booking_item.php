<div class="col-lg-6 d-flex">
    <div class="container shadow flex-fill">
    <br>
        <div class="col-12">
            <img src="<?= ROOT . '/ressources/uploads/profil/' . $booking['profil_image'] ?>" alt="Image de profil" class="rounded-circle profil-img" />
        </div>
        <h3><?= $booking['first_name'] . ' ' . $booking['last_name'] ?></h3>
        <p><i class="bi bi-people-fill h4"></i><?= $booking['seat_number'] ?></p>
        <div class="row justify-content-center">
            <div class="col-4">
                <?php if ($booking['status'] == "Validée"): ?>
                    <span class="btn btn-success">Validée</span>
                <?php elseif ($booking['status'] == 'En attente'): ?>
                    <span class="btn btn-warning">En attente</span>
                    <div class="row justify-content-center">
                        <div class="col-6">
                           
                            <a href="<?= ROOT ?>/reservations/traiter/0/<?= $booking['id_booking_pk'] ?>" class="text-dark mr-3 btn"><i class="bi bi-x-circle-fill h3 text-danger"></i></a>
                            
                            
                        </div>
                        <div class="col-6">
                        <a href="<?= ROOT ?>/reservations/traiter/1/<?= $booking['id_booking_pk'] ?>" class="text-dark btn"><i class="bi bi-check-circle-fill h3 text-success"></i></a>
                </div>
                    </div>
                <?php else: ?>
                    <span class="btn btn-danger">Refusée</span>
                <?php endif; ?>
            </div>
        </div>
        <?php if($params['accessLevel'] == 3){

            include('booking_contact_info.php');
        }?>
        <br>
    </div>
</div>
