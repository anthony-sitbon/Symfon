<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\AdvertEditType;
use OC\PlatformBundle\Entity\Image;
//use OC\PlatformBundle\Form\ImageType;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdvertController extends Controller {

    public function indexAction($page) {
        // generer des routes
        // methode longue
//        $url = $this->get('router')->generate(
//                'oc_platform_view',
//                array('id' => 5)
//        );
        // methode longue
//        $url = $this->get('router')->generate('oc_platform_view');
        // methode courte
//        $url = $this->generateUrl('oc_platform_home');
        //pour en voyer dans un email une url absolue :
//        $url = $this->get('router')->generate('oc_platform_home', array(), true);
//        return new Response("L'URL de l'annonce d'id 5 est : ".$url);
//        //old version
//        $content = $this
//                ->get('templating')
//                ->render('OCPlatformBundle:Advert:index.html.twig', array(
//                    'nom' => 'coucou'
//                ));
//        return new Response($content);
        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 3;

        $doctrine = $this->getDoctrine();

        $em = $doctrine->getManager(); // With autocompletion
//        $em = $this->get('doctrine.orm.entity_manager'); // Without autocompletion
        $advertRepository = $em->getRepository('OCPlatformBundle:Advert');


        // Notre liste d'annonce en dur
//        $listAdverts = array(
//            array(
//                'title' => 'Recherche développpeur Symfony2',
//                'id' => 1,
//                'author' => 'Alexandre',
//                'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
//                'date' => new \Datetime()),
//            array(
//                'title' => 'Mission de webmaster',
//                'id' => 2,
//                'author' => 'Hugo',
//                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
//                'date' => new \Datetime()),
//            array(
//                'title' => 'Offre de stage webdesigner',
//                'id' => 3,
//                'author' => 'Mathieu',
//                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
//                'date' => new \Datetime())
//        );

        $listAdverts = $advertRepository->getAdverts($page, $nbPerPage);

        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $listAdverts,
                    'nbPages' => $nbPages,
                    'page' => $page
        ));
    }

    // La route fait appel à OCPlatformBundle:Advert:view,
    // on doit donc définir la méthode viewAction.
    // On donne à cette méthode l'argument $id, pour
    // correspondre au paramètre {id} de la route

    public function viewAction($id) {

        // STATIC DATA //
//        $advert = array(
//            'title' => 'Recherche développpeur Symfony2',
//            'id' => $id,
//            'author' => 'Alexandre',
//            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
//            'date' => new \Datetime()
//        );
        // END STATIC DATA //
        // 
        // 
        // On récupère le repository
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OCPlatformBundle:Advert');

        // On récupère l'entité correspondante à l'id $id
        $advert = $repository->find($id);

        // autre méthode pour récupérer un id sans le repository,
        // mais directement depuis l'EntityManager
//        $advert = $this->getDoctrine()
//            ->getManager()
//            ->find('OCPlatformBundle:Advert', $id);
        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // On récupère la liste des candidatures de cette annonce
        $listApplications = $em
                ->getRepository('OCPlatformBundle:Application')
                ->findByAdvert($advert)
        ;

        // On récupère maintenant la liste des AdvertSkill
        $listAdvertSkills = $em
                ->getRepository('OCPlatformBundle:AdvertSkill')
                ->findByAdvert($advert)
        ;

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
                    'advert' => $advert,
                    'listApplications' => $listApplications,
                    'listAdvertSkills' => $listAdvertSkills
        ));
    }

//    public function viewAction($id, Request $request) {
//        // $id vaut 5 si l'on a appelé l'URL /platform/advert/5
//        // Ici, on récupèrera depuis la base de données
//        // l'annonce correspondant à l'id $id.
//        // Puis on passera l'annonce à la vue pour
//        // qu'elle puisse l'afficher
//        // On récupère notre paramètre tag
////      $tag = $request->query->get('tag');
////      return new Response("Affichage de l'annonce d'id : ".$id.", avec le tag : ".$tag);
//
//        return $this->render(
//                        'OCPlatformBundle:Advert:view.html.twig', array('id' => $id)
//        );
//
////        return $this->redirectToRoute('oc_platform_home');
////        return new JsonResponse(array('id' => $id));
////        $session = $request->getSession();
////        
////        $uerId = $session->get('user_id');
////        
////        $session->set('user_id', 91);
////        
////        return new Response("je suis une page de test, je n'ai rien à dire");
//    }

    /**
     * 
     * @param Request $request
     * @return type
     * @throws AccessDeniedException
     * 
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request) {

        // METHODE 1 - Utiliser directement le service security.context //
//        // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
//        if (!$this->get('security.context')->isGranted('ROLE_AUTEUR')) {
//            // Sinon on déclenche une exception « Accès interdit »
//            throw new AccessDeniedException('Accès limité aux auteurs.');
//        }
//
//        // Ici l'utilisateur a les droits suffisant,
//        // on peut ajouter une annonce
        // END METHODE 1 - Utiliser directement le service security.context //
        // 
        // METHODE 2 - Les annotations //
        // @Security("has_role('ROLE_AUTEUR')")
        // @Security("has_role('ROLE_AUTEUR') and has_role('ROLE_AUTRE')")
        // END METHODE 2 - Les annotations //
        // 
        // FLASH MESSAGE //
//        $session = $request->getSession();
//        
//        $session->addFlash('info', 'Annonce bien enregistrée');
//        
//        $session->addFlash('info', 'Oui oui, elle est bien enregistrée !');
//        
//        return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        // END FLASH MESSAGE //
        // ANTISPAM SERVICE //
//        // On récupère le service
//        $antispam = $this->get('oc_platform.antispam');
//
//        // Je pars du principe que $text contient le texte d'un message quelconque
//        $text = '...';
//        if ($antispam->isSpam($text)) {
//          throw new \Exception('Votre message a été détecté comme spam !');
//        }
        // END ANTISPAM SERVICE //
        // 
        // 
        // ENTITY //
//        $advert = new Advert();
//        $advert->setTitle('Recherche développeur Symfony 2 de toute urgence');
//        $advert->setAuthor('Alexandre');
//        $advert->setContent("Nous recherchons un développeur Symfony2 débutant sur Paris. Blabla…");
//
//        // Création d'une première candidature
//        $application1 = new Application();
//        $application1->setAuthor('Marine');
//        $application1->setContent("J'ai toutes les qualités requises.");
//
//        // Création d'une deuxième candidature par exemple
//        $application2 = new Application();
//        $application2->setAuthor('Pierre');
//        $application2->setContent("Je suis très motivé.");
//
//        // On lie les candidatures à l'annonce
//        $application1->setAdvert($advert);
//        $application2->setAdvert($advert);
//
//        // On peut ne pas définir ni la date ni la publication,
//        // car ces attributs sont définis automatiquement dans le constructeur
//        // Création de l'entité Image
//        $image = new Image();
//        $image->setUrl('http://www.tf1pub.fr/sites/default/files/logo-tf1pub_0.png');
//        $image->setAlt('Job de rêve');
//
//        // On lie l'image à l'annonce
//        $advert->setImage($image);
//
//        // On récupère l'EntityManager
//        $em = $this->getDoctrine()->getManager();
//
//        // On récupère toutes les compétences possibles
//        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
//
//        // Pour chaque compétence
//        foreach ($listSkills as $skill) {
//            // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
//            $advertSkill = new AdvertSkill();
//
//            // On la lie à l'annonce, qui est ici toujours la même
//            $advertSkill->setAdvert($advert);
//            // On la lie à la compétence, qui change ici dans la boucle foreach
//            $advertSkill->setSkill($skill);
//
//            // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
//            $advertSkill->setLevel('Expert');
//
//            // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
//            $em->persist($advertSkill);
//        }
//
//        // Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas définit la relation AdvertSkill
//        // avec un cascade persist (ce qui est le cas si vous avez utilisé mon code), alors on doit persister $advert
//        // Etape 1 : On "persiste" l'entité
//        $em->persist($advert);
//
//        // Étape 1 bis : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
//        // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
//        $em->persist($application1);
//        $em->persist($application2);
//
//        // Etape 2 : On "flush" tout ce qui a été persisté avant
//        $em->flush();
        // END ENTITY //
//        if ($request->isMethod('POST')) {
//
//            $request->getSession()->getFlashBag()->add('info', 'Annonce bien enregistrée.');
//
////            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
//            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => '1')));
//        }
        // On crée un objet Advert
        $advert = new Advert();

        // On crée le FormBuilder grâce au service form factory
        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        // $form = $this->get('form.factory')->create(new AdvertType, $advert);
        // Raccourcis :
        $form = $this->createForm(new AdvertType(), $advert, array(
            'action' => $this->generateUrl('oc_platform_add'),
        ));
        //
        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
        // 
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
        // On vérifie que les valeurs entrées sont correctes
        // (Nous verrons la validation des objets en détail dans le prochain chapitre)
        if ($form->handleRequest($request)->isValid()) {

            // On l'enregistre notre objet $advert dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function editAction($id, Request $request) {

//        if ($request->isMethod('POST')) {
//            $request->getSession()->getFlashBag()->add('notice', 'Annone bien modifiée.');
//
//            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
//        }
        // STATIC //
//        $advert = array(
//            'title' => 'Recherche développpeur Symfony2',
//            'id' => $id,
//            'author' => 'Alexandre',
//            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
//            'date' => new \Datetime()
//        );
        // END STATIC //

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // FIXTURE ADD TO CATEGORY //
//        // La méthode findAll retourne toutes les catégories de la base de données
//        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();
//
//        // On boucle sur les catégories pour les lier à l'annonce
//        foreach ($listCategories as $category) {
//            $advert->addCategory($category);
//        }
//
//        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
//        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
//        // Étape 2 : On déclenche l'enregistrement
//        $em->flush();
        // END FIXTURE ADD TO CATEGORY //

        $form = $this->createForm(new AdvertEditType(), $advert, array(
            'action' => $this->generateUrl('oc_platform_edit', array('id' => $id)),
        ));

        if ($form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce ' . $id . ' bien modifiée.');

            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
                    'form' => $form->createView(),
                    'advert' => $advert
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id, Request $request) {

        // OLD STATIC //
//        $advert = array(
//            'title' => 'Recherche développpeur Symfony2',
//            'id' => $id,
//            'author' => 'Alexandre',
//            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
//            'date' => new \Datetime()
//        );
        // END OLD STATIC //

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // FIXTURE ADD TO CATEGORY //
//        // On boucle sur les catégories de l'annonce pour les supprimer
//        foreach ($advert->getCategories() as $category) {
//            $advert->removeCategory($category);
//        }
//
//        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
//        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
//        // On déclenche la modification
//        $em->flush();
        // END FIXTURE ADD TO CATEGORY //
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createFormBuilder()->getForm();

//        if ($request->isMethod('POST')) {

        if ($form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            // Puis on redirige vers l'accueil
            return $this->redirect($this->generateUrl('oc_platform_home'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de delete
        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
                    'advert' => $advert,
                    'form' => $form->createView()
        ));
    }

    public function viewSlugAction($slug, $year, $_format) {
        return new Response(
                "On pourrait afficher l'annonce correspondant au
            slug '" . $slug . "', créée en " . $year . " et au format " . $_format . "."
        );
    }

    public function exitAction() {
        $content = $this
                ->get('templating')
                ->render('OCPlatformBundle:Advert:exit.html.twig', array(
            'who' => 'my friend'
        ));
        return new Response($content);
    }

    public function menuAction($limit = 3) {

        // STATIC //
//        // On fixe en dur une liste ici, bien entendu par la suite
//        // on la récupérera depuis la BDD !
//        $listAdverts = array(
//            array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
//            array('id' => 5, 'title' => 'Mission de webmaster'),
//            array('id' => 9, 'title' => 'Offre de stage webdesigner')
//        );
//
        // END STATIC //

        $listAdverts = $this->getDoctrine()
                ->getManager()
                ->getRepository('OCPlatformBundle:Advert')
                ->findBy(
                array(), // Pas de critère
                array('date' => 'desc'), // On trie par date décroissante
                $limit, // On sélectionne $limit annonces
                0                        // À partir du premier
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
                    // Tout l'intérêt est ici : le contrôleur passe
                    // les variables nécessaires au template !
                    'listAdverts' => $listAdverts
        ));
    }

    public function editImageAction($advertId) {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($advertId);

        // On modifie l'URL de l'image par exemple
        $advert->getImage()->setUrl('test.png');

        // On n'a pas besoin de persister l'annonce ni l'image.
        // Rappelez-vous, ces entités sont automatiquement persistées car
        // on les a récupérées depuis Doctrine lui-même
        // On déclenche la modification
        $em->flush();

        return new Response('OK');
    }

    public function testAction() {
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('OCPlatformBundle:Advert')
        ;

        $listAdverts = $repository->myFindAll();

        // ...
    }

    public function listAction() {
        $listAdverts = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('OCPlatformBundle:Advert')
                ->getAdvertWithApplications()
        ;

        foreach ($listAdverts as $advert) {
            // Ne déclenche pas de requête : les candidatures sont déjà chargées !
            // Vous pourriez faire une boucle dessus pour les afficher toutes
            $advert->getApplications();
        }

        // …
    }

    public function testslugAction() {
        $advert = new Advert();
        $advert->setTitle("Recherche développeur !")
                ->setAuthor("Yoyo")
                ->setContent("bla bla bla ...");

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush(); // C'est à ce moment qu'est généré le slug

        return new Response('Slug généré : ' . $advert->getSlug());
        // Affiche « Slug généré : recherche-developpeur »
    }

    public function testvalidatorAction() {
        $advert = new Advert;

        $advert->setDate(new \Datetime());  // Champ « date » OK
        $advert->setTitle('abc');           // Champ « title » incorrect : moins de 10 caractères
        //$advert->setContent('blabla');    // Champ « content » incorrect : on ne le définit pas
        $advert->setAuthor('A');            // Champ « author » incorrect : moins de 2 caractères
        // On récupère le service validator
        $validator = $this->get('validator');

        // On déclenche la validation sur notre object
        $listErrors = $validator->validate($advert);

        // Si le tableau n'est pas vide, on affiche les erreurs
        if (count($listErrors) > 0) {
            return new Response('<pre>' . print_r($listErrors, true) . '</pre>');
        } else {
            return new Response("L'annonce est valide !");
        }
    }

}
