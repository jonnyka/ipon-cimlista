<?php

namespace Sed\UniOfficeManager\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SiteUpdateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dump', 'checkbox', array(
                'label' => 'sed_uniofficemanager_app_bundle.site.dump',
            ))
            ->add('gitpull', 'checkbox', array(
                'label' => 'sed_uniofficemanager_app_bundle.site.gitPull',
            ))
            ->add('npmbower', 'checkbox', array(
                'label' => 'sed_uniofficemanager_app_bundle.site.npmBower',
            ))
            ->add('build', 'checkbox', array(
                'label' => 'sed_uniofficemanager_app_bundle.site.build',
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sed_uniofficemanager_appbundle_siteupdate';
    }
}
