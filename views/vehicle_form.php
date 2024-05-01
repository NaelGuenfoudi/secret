<?php include("header.php"); ?>

<div class="container shadow p-3 mb-5 bg-white rounded">
    <h1 class="text-center"><?= ($params['action'] == "add") ? 'Ajouter un véhicule' : 'Modifier un véhicule' ?></h1>

    <!-- Formulaire ajout de véhicule -->

    <form action="<?= ($params['action'] == 'add') ? (ROOT . "/mon_espace/vehicule/ajouter") : (ROOT . "/mon_espace/vehicule/sauvegarder/" . $params['vehicleInfos']['id_vehicle_pk']); ?>" method="post">

        <div class="row justify-content-around">
            <!-- Nom du véhicule -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="model">Modèle</label>
                <input type="text" class="form-control" id="model" aria-describedby="emailHelp" name="model" required value="<?= isset($params['vehicleInfos']['name']) ? $params['vehicleInfos']['name'] : '' ?>">
                <?php if(isset($params['vehicleInfos']['model'])): ?>
                    <p class="text-danger"><?= $params['vehicleInfos']['model'] ?></p>
                <?php endif; ?> 
            </div>
            <!-- Couleur du véhicule -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="color">Couleur</label>
                <select name="color" id="color" class="form-control">
                    <?php if(isset($params['vehicleInfos']['color'])): ?>
                        <option value="<?= $params['vehicleInfos']['color'] ?>"><?= $params['vehicleInfos']['color_name'] ?></option>
                    <?php endif; ?>
                    <option value="1">Blanche</option>
                    <option value="2">Noire</option>
                    <option value="3">Jaune</option>
                    <option value="4">Rouge</option>
                    <option value="5">Vert foncé</option>
                    <option value="6">Vert pomme</option>
                    <option value="7">Grise</option>
                    <option value="8">bleu clair</option>
                    <option value="9">Bleu foncé</option>
                    <option value="10">Orange</option>
             
                </select>
            </div>
            <!-- nombre de place du véhicule -->
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="seat_number">Nombre de places :</label>
                <select name="seat_number" id="seat_number" class="form-control">
                    <?php if(isset($params['vehicleInfos']['seat_number'])): ?>
                        <option value="<?= $params['vehicleInfos']['seat_number'] ?>"><?= $params['vehicleInfos']['seat_number'] ?></option>
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
        </div>
        <div class="row justify-content-center">
            <div class="col-4 text-center">
                <button type="submit" class="btn bg-black text-warning"><?= ($params['action'] == "add") ? 'Ajouter' : 'Modifier' ?></button>
            </div>
        </div>
    </form>
</div>
