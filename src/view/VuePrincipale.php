<?php

namespace GEG\view;

require_once "src/date.php";

class VuePrincipale
{

  private $t;

  public function __construct($tab)
  {
    $this->t = $tab;
  }


  public function render($isAdmin)
  {
    if ($isAdmin) {
      $adminButtons = <<<END
<a href="ajouterCreneau" class="btn btn-primary">Ajouter un créneau</a>
<a href="ajouterCreneau" class="btn btn-primary">Inscrire un utilisateur</a>
END;
    } else {
      $adminButtons = "";
    }

    $vGenerale = new VueGenerale();
    $html = "";
    $content = "";


    $numSemaine = -1;
    $numCycle = -1;
    $numJour = -1;

    foreach ($this->t as $value) {


      $id = $value->id;

      $date = calc_date("1970-01-05", $value->semaine, $value->jour, $value->cycle);

      $style = ($numSemaine != $value->semaine || $numCycle != $value->cycle || $numJour != $value->jour) ? "numSem" . $value->semaine : "";

      $numSemaine = $value->semaine;
      $numCycle = $value->cycle;
      $numJour = $value->jour;

      $styleBord = "numSem" . $value->semaine;


      $content .= <<<END
  <div class ="$styleBord" id="crenau-$id">
    <span class="pl-4">$date->jour_nom $date->jour_no $date->mois_nom $date->annee_no de {$value->debutHeure}h à {$value->finHeure}h</span>
    <button type="button" class="float-right btn btn-danger" data-target="#creneau$id" data-toggle="modal">Modifier</button>
    <hr class="$style">
  </div>

  <div class="modal fade" id="creneau$id" tabindex="-1" role="dialog" aria-labelledby="creneauLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="creneauLabel">Modifier créneau du $date->jour_nom $date->jour_no $date->mois_nom $date->annee_no de {$value->debutHeure}h à {$value->finHeure}h</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <a href="ajouterBesoin/$id" class="btn btn-success">Ajouter un besoin</a>
        </div>
      </div>
    </div>
  </div>
END;
    }
    $html = <<<END
    <section>
    <div class="container">
      <div class="row mt-5 shadow-sm " style="height: 100%; background-color: rgba(242,242,242,.8);">
        <div class="container">
          <div class="text-center w-100">
            <h3 class="text-uppercase mt-2">Créneaux :</h3>
            $adminButtons
          </div>
          <hr>
          $content
        </div>
      </div>
    </div>
  </section>
END;
    $vGenerale->render($html);
  }
}
