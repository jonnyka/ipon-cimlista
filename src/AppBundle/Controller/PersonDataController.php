<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\PersonData;
use AppBundle\Form\PersonDataType;

/**
 * PersonData controller.
 *
 * @Route("/persondata")
 */
class PersonDataController extends Controller
{

    /**
     * Lists all PersonData entities.
     *
     * @Route("/", name="persondata")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:PersonData')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new PersonData entity.
     *
     * @Route("/", name="persondata_create")
     * @Method("POST")
     * @Template("AppBundle:PersonData:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new PersonData();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('persondata_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a PersonData entity.
     *
     * @param PersonData $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PersonData $entity)
    {
        $form = $this->createForm(new PersonDataType(), $entity, array(
            'action' => $this->generateUrl('persondata_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PersonData entity.
     *
     * @Route("/new", name="persondata_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PersonData();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a PersonData entity.
     *
     * @Route("/{id}", name="persondata_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:PersonData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonData entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PersonData entity.
     *
     * @Route("/{id}/edit", name="persondata_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:PersonData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonData entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a PersonData entity.
    *
    * @param PersonData $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PersonData $entity)
    {
        $form = $this->createForm(new PersonDataType(), $entity, array(
            'action' => $this->generateUrl('persondata_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing PersonData entity.
     *
     * @Route("/{id}", name="persondata_update")
     * @Method("PUT")
     * @Template("AppBundle:PersonData:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:PersonData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PersonData entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('persondata_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a PersonData entity.
     *
     * @Route("/{id}", name="persondata_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:PersonData')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PersonData entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('persondata'));
    }

    /**
     * Creates a form to delete a PersonData entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('persondata_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
