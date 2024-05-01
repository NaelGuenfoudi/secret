<?php 
//Il s'agit d'un menu dynamique qui affice la liste des sections definie si dessous. La section "active" est mise en évidebce
$sectionList = [
    "section_profil" =>['action'  => "/mon_espace/profil", 'name'=> 'Profil'],
    "section_vehicle" =>['action'  => "/mon_espace/vehicule", 'name'=> 'Véhicules'],
    "section_ride" =>['action'  => "/mon_espace/trajets", 'name'=> 'Trajets'],
    "section_booking"=>['action' => "/mon_espace/reservations/", 'name' => 'Réservations'],
    "section_requests"=>['action' => "/mon_espace/demandes/", 'name' => 'Demandes']

];
if(!isset($params['section_name'])){
    $params['section_name'] = '';
}
foreach ($sectionList as $key => $section) {
    if ($key == $params['section_name']) {
        echo '
        <li class="list-group-item rounded shadow-sm mb-2 bg-light">
            <a href="' .ROOT . $section['action'] . '" class="text-decoration-none text-dark">'. $section['name'].'</a>
        </li>';
    }else{

        echo '
        <li class="list-group-item rounded shadow-sm mb-2">
        <a href="' .ROOT . $section['action'] . '" class="text-decoration-none text-dark">'. $section['name'].'</a>
        </li>';
    }


}