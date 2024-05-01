<?php include("header.php"); ?>
<html>
<head>
    <link rel="stylesheet" href="../public/css/app.css">
    <link rel="stylesheet" href="../public/css/custom.css">
</head>
<body>

    <!--Image à insérer et tester le fait que la zone de recherche se superpose avec l'image-->
    <section id="home" class="d-flex align-items-center">

<div class="container">
    <div class="row">
    <div class="col-lg-6  hero-img " data-aos="zoom-in" data-aos-delay="200">
        <img src="<?=ROOT.'/ressources/site/landing.png' ?>" class="img-fluid" alt="">
    </div>
    <div class="col-lg-6 d-flex flex-column justify-content-center ">
    <?php include(VIEWS."/components/search_form.php"); ?>

    </div>
        </div>
    </div>

    </div>
</div>

</section>
        

        
</body>
</html>
<script src="<?= SCRIPTS ?>js/jquery-3.7.1.min.js"></script>
    <script src="<?= SCRIPTS ?>js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?= SCRIPTS ?>/css/jquery-ui.min.css">
    <script src="<?= SCRIPTS ?>/js/city_autocomplete.js"></script>