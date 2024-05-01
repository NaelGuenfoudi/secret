
<form action="<?= ($params['accessLevel'] == 0) ? ROOT.'/reserver/'.$params['rideInfos']['id_ride_pk'] : ROOT.'/reserver/modifier/'.$params['userBooking']['id_booking_pk'] ?>" method="post">
<label for="seat_number">Choisissez un nombre de place à réserver :</label>
<select name="seat_number" id="seat_number" class="form-control">
    <?php 

if(isset($params['rideInfos']['seats']['remaining_seat'])) {

    for($i = 1; $i <= $params['rideInfos']['seats']['remaining_seat']; $i++) {
        // Générer une option pour chaque valeur de $i
        if(isset($params['userBooking']) && $params['userBooking']['seat_number'] == $i) {
            echo '<option selected value="' . $i . '">' . $i . '</option>';
        } else {
            echo '<option value="' . $i . '">' . $i . '</option>';
        }
    }
}

?>


</select>
<br>
<!--- Champs cachés pour transmettre des informations utile dans le formulaire !-->


<div class="text-center">
<button type="submit"class="btn bg-black text-warning"><?= ($params['accessLevel'] == 0) ? 'Réserver' : 'Modifier ma réservation' ?></button>

</div>
</form>
