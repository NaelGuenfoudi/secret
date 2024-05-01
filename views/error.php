
    <!---Une vue affichant un message d'erreur
    A mettre en page ! !--->
    <?php include(VIEWS."header.php"); ?>
    <div class="container  shadow p-3 mb-5 bg-white rounded" style="width:80%">

    <h1 class="text-center">Aïe...Une erreur est survenue...</h1>
    <p class="text-center"> Message : <?= $params['error_msg'] ?> </p>
    <div class="row justify-content-center">
        <div class="col-4 text-center">
            <a class="btn bg-black text-warning text-center mx-auto" href="<?= ROOT . '/index' ?>">Retour à l'accueil</a>
        </div>
    </div>


</div>