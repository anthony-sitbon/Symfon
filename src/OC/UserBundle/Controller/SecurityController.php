<?php

namespace OC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {

    /**
     * On modifie la façon dont est choisie la vue lors du rendu du formulaire de connexion
     *
     * Je sais que c'est cette méthode qu'il faut hériter car j'ai été voir le contrôleur d'origine du bundle :
     * https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Controller/SecurityController.php
     */
    protected function renderLogin(array $data) {
        // Sur la page du formulaire de connexion, on utilise la vue classique "login"
        // Cette vue hérite du layout et ne peut donc être utilisée qu'individuellement
        if ($this->container->get('request')->attributes->get('_route') == 'fos_user_security_login') {
            $view = 'login';
        } else {
            // Mais sinon, il s'agit du formulaire de connexion intégré au menu, on utilise la vue "login_content"
            // car il ne faut pas hériter du layout !
            $view = 'login_content';
        }

        $template = sprintf('FOSUserBundle:Security:%s.html.twig', $view);

        return $this->container->get('templating')->renderResponse($template, $data);
    }

//    public function loginAction(Request $request) {
//
//        // UTILISATEUR COURANT -- METHODE LONGUE (CONTROLEUR ET SERVICE) //
////        // On récupère le service
////        $security = $this->get('security.context');
////
////        // On récupère le token
////        $token = $security->getToken();
////
////        // Si la requête courante n'est pas derrière un pare-feu, $token est null
////        // Sinon, on récupère l'utilisateur
////        $user = $token->getUser();
////
////        // Si l'utilisateur courant est anonyme, $user vaut « anon. »
////        // Sinon, c'est une instance de notre entité User, on peut l'utiliser normalement
////        $user->getUsername();
//        // END UTILISATEUR COURANT -- METHODE LONGUE (CONTROLEUR ET SERVICE) //
//        // 
//        // METHODE 1 - Utiliser directement le service security.context //
//        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
//        //Le role IS_AUTHENTICATED_REMEMBERED correspond à un utilisateur authentifié
//        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
////            return $this->redirectToRoute('oc_platform_home');
//            return $this->redirect($this->generateUrl('oc_platform_view'));
//        }
//
//        // Le service authentication_utils permet de récupérer le nom d'utilisateur
//        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
//        // (mauvais mot de passe par exemple)
//        $authenticationUtils = $this->get('security.authentication_utils');
//
//        // END METHODE 1 - Utiliser directement le service security.context //
//
//        return $this->render('OCUserBundle:Security:login.html.twig', array(
//                    'last_username' => $authenticationUtils->getLastUsername(),
//                    'error' => $authenticationUtils->getLastAuthenticationError(),
//        ));
//    }
}
