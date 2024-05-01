<form action=<?= ROOT."/rechercher"?> method="post">
        <div class="row justify-content-around shadow p-3 mb-5 bg-white rounded ">
        <h2 class="fw-bold">Rechercher un trajet</h2>
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="startCity" class="form-label">Départ</label>
                <input type="text" class="form-control city_input" id="startCity" name="startCity" placeholder="Entrez une ville" >
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="endCity" class="form-label">Arrivée</label>
                <input type="text" class="form-control city_input"  id="endCity" name="endCity" placeholder="Entrez une ville" >
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" >
            </div>

        
        <?php
            if(isset($params['searchMsg'])){
                echo '<p class="alert alert-danger">'.$params['searchMsg'].'</p>'; 
            }
        ?>
        <button type="submit" class="btn bg-black text-warning">Rechercher</button>
        </div>
    </form>