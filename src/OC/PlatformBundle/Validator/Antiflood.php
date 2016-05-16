<?php

namespace OC\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Antiflood extends Constraint {

    public $message = "Vous avez déjà posté un message il y a moins de %secondes% secondes, merci d'attendre un peu.";
    public $secondes = 30;

//    public $message = "Votre message \"%string%\" est considéré comme flood";

    public function validatedBy() {
        return 'oc_platform_antiflood'; // Ici, on fait appel à l'alias du service
    }

}
