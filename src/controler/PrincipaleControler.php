<?php

namespace GEG\controler;

use \Illuminate\Database\Capsule\Manager as DB;
use GEG\model\Liste;
use GEG\model\Item;
use GEG\model\Creneau;
use GEG\view\VuePrincipale;


class PrincipaleControler
{
    public function afficher()
    {
      $t = Creneau::orderBy("cycle")->orderBy("semaine")->orderBy("jour")->orderBy("debutHeure")->get();
      $v = new VuePrincipale($t);
      $v->render();
    }
}
