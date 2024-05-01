<div class="row justify-content-around shadow p-3 mb-5 bg-white rounded ">
        <h2 class="fw-bold">Résultat de votre recherche</h2>
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="startCity" class="form-label">Départ</label>
                <input type="text" class="form-control" id="startCity" name="startCity"  readonly type="text" value="<?= isset($params['searchParams']['startCity']) ? $params['searchParams']['startCityName'] : "-" ?>">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="endCity" class="form-label">Arrivée</label>
                <input type="text" class="form-control" id="endCity" name="endCity"readonly type="text" value="<?= isset($params['searchParams']['endCity']) ? $params['searchParams']['endCityName'] : "-" ?>" >
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" readonly type="text"name="date" value="<?= isset($params['searchParams']['date']) ? $params['searchParams']['date'] : "-" ?>">
            </div>

        
        <?php
            if(isset($params['searchMsg'])){
                echo '<p class="alert alert-danger">'.$params['searchMsg'].'</p>'; 
            }
        ?>
        <a href="<?=ROOT ?>"class="btn bg-black text-warning">Nouvelle recherche</a>
        </div>