<?php

namespace Sed\UniOfficeManager\AppBundle\Controller;

use Sed\UniOfficeManager\AppBundle\Form\SiteUpdateType;
use Sed\UniOfficeManager\AppBundle\Service\DeployManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sed\UniOfficeManager\AppBundle\Entity\Site;
use Sed\UniOfficeManager\AppBundle\Form\SiteType;

/**
 * Site controller.
 *
 * @Route("/sites")
 */
class SiteController extends Controller
{
    /**
     * Lists all Site entities.
     *
     * @Route("/", name="site")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sites = $em->getRepository('SedUniOfficeManagerAppBundle:Site')->findAll();

        return array(
            'sites' => $sites,
            'menu' => 'site',
        );
    }
    /**
     * Creates a new Site entity.
     *
     * @Route("/new/", name="site_create")
     * @Method("POST")
     * @Template("SedUniOfficeManagerAppBundle:Site:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $site = new Site();
        $form = $this->createCreateForm($site);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $site->setLastUpdated(new \DateTime());
            $site->setDirectory(rtrim($site->getDirectory(), "/"));

            $em->persist($site);
            $em->flush();

            /** @var DeployManager $manager */
            $manager = $this->get('deploy_manager');
            $manager->cloneRepository($site->getGitrepo(), $site->getDirectory());
            $manager->npmAndBower($site->getGitrepo(), $site->getDirectory());
            $manager->createLogDirectory($site->getName());
            $manager->createDb($site->getName());
            //$manager->gruntBuild($site->getName(), $site->getDirectory());

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('sed_uniofficemanager_app_bundle.site.siteCreated'));

            return $this->redirect($this->generateUrl('site'));
        }

        return array(
            'site' => $site,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Site entity.
     *
     * @param Site $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Site $entity)
    {
        $form = $this->createForm(new SiteType(false, $this->get('get_gitlab_projects')), $entity, array(
            'action' => $this->generateUrl('site_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'sed_uniofficemanager_app_bundle.site.siteCreate',
            'attr' => array('class' => 'btn-success  pull-left marginRight15')
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Site entity.
     *
     * @Route("/new/", name="site_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $site = new Site();
        $form = $this->createCreateForm($site);

        return array(
            'site' => $site,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Site entity.
     *
     * @Route("/{id}/", name="site_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('SedUniOfficeManagerAppBundle:Site')->find($id);
        if (!$site) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'site' => $site,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{id}/edit/", name="site_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('SedUniOfficeManagerAppBundle:Site')->find($id);
        if (!$site) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        $editForm = $this->createEditForm($site);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'site' => $site,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Site entity.
    *
    * @param Site $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Site $entity)
    {
        $form = $this->createForm(new SiteType(true, $this->get('get_gitlab_projects')), $entity, array(
            'action' => $this->generateUrl('site_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'sed_uniofficemanager_app_bundle.site.siteUpdate',
            'attr' => array('class' => 'btn-success  pull-left marginRight15')
        ));

        return $form;
    }
    /**
     * Edits an existing Site entity.
     *
     * @Route("/{id}/", name="site_update")
     * @Method("PUT")
     * @Template("SedUniOfficeManagerAppBundle:Site:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('SedUniOfficeManagerAppBundle:Site')->find($id);
        if (!$site) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($site);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $site->setDirectory(rtrim($site->getDirectory(), "/"));
            $em->persist($site);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('sed_uniofficemanager_app_bundle.site.siteUpdated'));

            return $this->redirect($this->generateUrl('site'));
        }

        return array(
            'site' => $site,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Site entity.
     *
     * @Route("/{id}/", name="site_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $site = $em->getRepository('SedUniOfficeManagerAppBundle:Site')->find($id);
            if (!$site) {
                throw $this->createNotFoundException('Unable to find Site entity.');
            }

            /** @var DeployManager $manager */
            $manager = $this->get('deploy_manager');
            $manager->deleteSiteDir($site->getDirectory());

            $em->remove($site);
            $em->flush();

            $this->get('session')->getFlashBag()->add('warning',
                $this->get('translator')->trans('sed_uniofficemanager_app_bundle.site.siteDeleted'));
        }

        return $this->redirect($this->generateUrl('site'));
    }

    /**
     * Creates a form to delete a Site entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('site_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit',  array(
                'label' => 'sed_uniofficemanager_app_bundle.site.siteDelete',
                'attr' => array('class' => 'btn-danger pull-left marginRight15')
            ))
            ->getForm()
        ;
    }

    /**
     * Displays a form to update a site.
     *
     * @Route("/{id}/update-site/", name="update_site")
     * @Method("GET")
     * @Template()
     */
    public function updateSiteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('SedUniOfficeManagerAppBundle:Site')->find($id);
        if (!$site) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        $updateForm = $this->createUpdateForm($site);

        return array(
            'site' => $site,
            'update_form' => $updateForm->createView(),
        );
    }

    /**
     * Creates a form to update a site.
     *
     * @param Site $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createUpdateForm(Site $entity)
    {
        $form = $this->createForm(new SiteUpdateType(), array(
            'action' => $this->generateUrl('do_update_site', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'sed_uniofficemanager_app_bundle.site.siteUpdate',
            'attr' => array('class' => 'btn-success  pull-left marginRight15')
        ));

        return $form;
    }
    /**
     * Updates a site.
     *
     * @Route("/{id}/update-site/", name="do_update_site")
     * @Method("POST")
     * @Template("SedUniOfficeManagerAppBundle:Site:updateSite.html.twig")
     */
    public function doUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('SedUniOfficeManagerAppBundle:Site')->find($id);
        if (!$site) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        /** @var DeployManager $manager */
        $manager = $this->get('deploy_manager');
        $name = $site->getName();
        $gitrepo = $site->gitRepo();
        $directory = $site->getDirectory();
        $updateForm = $this->createUpdateForm($site);
        $updateForm->handleRequest($request);

        $data = $updateForm->getData();
        $doSomething = false;

        if ($data['dump'] == true) {
            $doSomething = true;
            $manager->dumpDb($name);
        }
        if ($data['gitpull'] == true) {
            $doSomething = true;
            $manager->gitPull($gitrepo, $directory);
        }
        if ($data['npmbower'] == true) {
            $doSomething = true;
            $manager->npmAndBower($gitrepo, $directory);
        }
        if ($data['build'] == true) {
            $doSomething = true;
            $manager->gruntBuild($gitrepo, $directory);
        }

        $em->flush();

        $flashBagMessage = 'sed_uniofficemanager_app_bundle.site.siteUpdateStarted';
        $type = 'notice';
        if (!$doSomething) {
            $flashBagMessage = 'sed_uniofficemanager_app_bundle.site.siteNotUpdated';
            $type = 'warning';
        }

        $this->get('session')->getFlashBag()->add($type,
            $this->get('translator')->trans($flashBagMessage));

        return $this->redirect($this->generateUrl('site'));
    }
}
