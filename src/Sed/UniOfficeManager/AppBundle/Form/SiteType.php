<?php

namespace Sed\UniOfficeManager\AppBundle\Form;

use Sed\UniOfficeManager\AppBundle\Service\GetGitlabProjects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SiteType extends AbstractType
{
    private $isEdit;
    private $getGitlabProjects;

    public function __construct($isEdit = false, GetGitlabProjects $getGitlabProjects)
    {
        $this->isEdit = $isEdit;
        $this->getGitlabProjects = $getGitlabProjects;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gitlabProjects = $this->getGitlabProjects->getProjects();

        $builder
            ->add('gitrepo', 'choice', array(
                'choices' => $gitlabProjects,
                'label' => 'sed_uniofficemanager_app_bundle.site.gitrepo',
            ))
            ->add('port', null, array(
                'label' => 'sed_uniofficemanager_app_bundle.site.port',
                'attr' => array(
                    'placeholder' => 'sed_uniofficemanager_app_bundle.site.portPlaceholder',
                ),
                'disabled' => $this->isEdit,
            ))
            ->add('name', 'text', array(
                'label' => 'sed_uniofficemanager_app_bundle.site.name',
                'attr' => array(
                    'placeholder' => 'sed_uniofficemanager_app_bundle.site.namePlaceholder',
                ),
                'disabled' => $this->isEdit,
            ))
            ->add('title', 'text', array(
                'label' => 'sed_uniofficemanager_app_bundle.site.title',
                'attr' => array(
                    'placeholder' => 'sed_uniofficemanager_app_bundle.site.titlePlaceholder',
                ),
            ))
            ->add('directory', 'text', array(
                'label' => 'sed_uniofficemanager_app_bundle.site.directory',
                'attr' => array(
                    'placeholder' => 'sed_uniofficemanager_app_bundle.site.directoryPlaceholder',
                ),
                'disabled' => $this->isEdit,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sed\UniOfficeManager\AppBundle\Entity\Site'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sed_uniofficemanager_appbundle_site';
    }
}
