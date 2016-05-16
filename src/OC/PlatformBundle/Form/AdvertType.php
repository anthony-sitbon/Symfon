<?php

namespace OC\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use OC\PlatformBundle\Entity\AdvertRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AdvertType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('date', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd/mm/yyyy'
                    ]
                ])
                ->add('title', 'text')
                ->add('content', 'textarea')
                ->add('author', 'text')
//        ->add('published', 'checkbox', array('required' => false))
                ->add('image', new ImageType())
                /*
                 * Rappel :
                 * * - 1er argument : nom du champ, ici « categories », car c'est le nom de l'attribut
                 * * - 2e argument : type du champ, ici « collection » qui est une liste de quelque chose
                 * * - 3e argument : tableau d'options du champ
                 */
//                ->add('categories', 'collection', array(
//                    'type' => new CategoryType(),
//                    'allow_add' => true,
//                    'allow_delete' => true
//                ))
                ->add('categories', 'entity', array(
                    'class' => 'OCPlatformBundle:Category',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
//        ->add('advert', 'entity', array(
//        'class' => 'OCPlatformBundle:Advert',
//        'property' => 'title',
//        'query_builder' => function(AdvertRepository $repo) {
//        return $repo->getPublishedQueryBuilder();
//        }))
//                ->add('save', 'submit')
        ;

        // fonction qui va écouter un évènement
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, // 1er argument : L'évènement qui nous intéresse
                function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
            // On récupère notre objet Advert sous-jacent
            $advert = $event->getData();

            // à la première création du formulaire, celui-ci exécute sa méthode setData() avec null en argument
            if (null === $advert) {
                return; // Cette occurrence de l'évènement PRE_SET_DATA ne nous intéresse pas, d'où la condition pour sortir de la fonction
            }

            if (!$advert->getPublished() || null === $advert->getId()) {
                // Si l'annonce n'est pas publiée, ou si elle n'existe pas encore en base (id est null),
                // alors on ajoute le champ published
                $event->getForm()->add('published', 'checkbox', array('required' => false));
            } else {
                // Sinon, on le supprime
                $event->getForm()->remove('published');
            }
        }
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'oc_platformbundle_advert';
    }

}
