<div class="row shadow p-3 mb-5 bg-white rounded">
<div class="col-lg-6 col-md-6 col-sm-12 ">
    <h2><?= $vehicle['name']; ?></h2>
    <p><?= $vehicle['color']; ?></p>
    <p><i class="bi bi-people-fill h4"> </i><?= $vehicle['seat_number']; ?></p>
</div>

<!-- Colonne pour les boutons de modification et de suppression -->
<div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
    <a href="<?= ROOT ?>/mon_espace/vehicule/modifier/<?= $vehicle['id_vehicle_pk']; ?>" class="text-dark mr-3 btn"><i class="bi bi-pencil-fill h3"></i></a>
    <a href="<?= ROOT ?>/mon_espace/vehicule/supprimer/<?= $vehicle['id_vehicle_pk']; ?>" class="text-dark btn"><i class="bi bi-trash-fill h3"></i></a>
</div>
</div>