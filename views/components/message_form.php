<br>
<hr class="hr hr-blurry">
<h2 class="h5">Messages</h2>
<hr class="hr hr-blurry">
<div class="container">
<?php 
if(empty($params['messageList'])) {
    echo '<p>Aucun message à afficher</p>';
} else {
    foreach($params['messageList'] as $message) {
        include('message.php');
    }
}
?>

</div>
<div class="container">
    <form method="post" action="<?= ROOT . '/message/envoyer' ?>">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="visually-hidden" for="message">Envoyer un message aux membres du trajet</label>
                    <input type="textarea" id="message" name="message" class="form-control" placeholder="Votre message">
                    <input type="hidden" name="rideId" value="<?=$params['rideInfos']['id_ride_pk'] ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group text-center">
                    <button type="submit" class="btn bg-black text-warning">Envoyer</button>
                </div>
            </div>
        </div>
    </form>
</div>
