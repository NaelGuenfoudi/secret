<?php include("header.php"); ?>

<div class="container shadow p-3 mb-5 bg-white rounded">
    <h1 class="text-center"><?= ($params['action'] == "add") ? 'Ajouter un trajet' : 'Modifier un trajet' ?></h1>

    <!-- Formulaire ajout de trajet -->
    <form action="<?= ($params['action'] == 'add') ? (ROOT . "/mon_espace/trajet/ajouter") : (ROOT . "/mon_espace/trajet/sauvegarder/" . $params['rideInfos']['id_ride_pk']); ?>" method="post">

        <div class="row justify-content-around">
            <!-- Ville de départ -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="start_city">Ville de départ</label>
                <input type="text" class="form-control" id="start_city" name="start_city" value="<?= isset($params['rideInfos']['start_city_name']) ? $params['rideInfos']['start_city_name'] : '' ?>" >
            </div>
            <!-- Ville d'arrivée -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="end_city">Ville d'arrivée</label>
                <input type="text" class="form-control" id="end_city" name="end_city" value="<?= isset($params['rideInfos']['end_city_name']) ? $params['rideInfos']['end_city_name'] : '' ?>"  >
            </div>
            <!-- Véhicule -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="vehicle">Véhicule</label>
                <select name="vehicle" id="vehicle" class="form-control" required>
                    <?php foreach ($params['vehiclesForUser'] as $vehicle): ?>
                        <option value="<?= $vehicle['id_vehicle_pk'] ?>"><?= $vehicle['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Nombre de places -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="seat_number">Nombre de places</label>
                <select name="seat_number" id="seat_number" class="form-control" required>
                    <?php if(isset($params['rideInfos']['seat_number'])): ?>
                        <option value="<?= $params['rideInfos']['seat_number'] ?>"><?= $params['rideInfos']['seat_number'] ?></option>
                    <?php endif; ?>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
            </div>
            <!-- Date et heure de départ -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="start_date">Date et heure de départ</label>
                <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
            </div>
            <!-- Date et heure d'arrivée -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="end_date">Date et heure d'arrivée</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
            </div>
            <!-- Description -->
            <div class="col-lg-8 mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= isset($params['rideInfos']['desc_message']) ? $params['rideInfos']['desc_message'] : '' ?></textarea>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-4 text-center">
                <button type="submit" class="btn bg-black text-warning"><?= ($params['action'] == "add") ? 'Ajouter' : 'Modifier' ?></button>
            </div>
        </div>
    </form>
</div>
