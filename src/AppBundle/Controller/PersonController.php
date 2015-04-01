<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Person;
use AppBundle\Form\PersonType;

/**
 * Person controller.
 */
class PersonController extends Controller
{

    /**
     * Get all Person entities.
     *
     * @Route("/person-datatable/", name="person_get_all_datatable", options={"expose" = true})
     * @Method("GET")
     */
    public function getPersonForDatatableAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $peopleObjects = $em->getRepository('AppBundle:Person')->findAll();

        $emptyValue = '-';

        $people = array();
        foreach ($peopleObjects as $po) {
            $people[] = array(
                "DT_RowId" => $po->getId(),
                "DT_RowClass" => 'pointer',
                'name' => $po->getName(),
                'emails' => $po->getEmails() ? $po->getEmails()[0] : $emptyValue,
                'phones' => $po->getPhones() ? $po->getPhones()[0] : $emptyValue,
                'addresses' => $po->getAddresses() ? $po->getAddresses()[0] : $emptyValue,
                'createdAt' => $po->getCreatedAt()->format('Y-m-d H:i:s'),
            );
        }

        return new JsonResponse($people);
    }

    /**
     * Get all Person entities.
     *
     * @Route("/person-datatable-admin/", name="person_get_all_admin_datatable", options={"expose" = true})
     * @Method("GET")
     */
    public function getPersonForDatatableAdminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $peopleObjects = $em->getRepository('AppBundle:Person')->findAll();

        $delimiter = '<br />';
        $emptyValue = '-';

        $people = array();
        foreach ($peopleObjects as $po) {
            $people[] = array(
                "DT_RowId" => $po->getId(),
                'name' => $po->getName(),
                'emails' => $po->getEmails() ? implode($delimiter, $po->getEmails()) : $emptyValue,
                'phones' => $po->getPhones() ? implode($delimiter, $po->getPhones()) : $emptyValue,
                'addresses' => $po->getAddresses() ? implode($delimiter, $po->getAddresses()) : $emptyValue,
                'createdAt' => $po->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $po->getUpdatedAt()->format('Y-m-d H:i:s'),
                'operations' => $po->getId(),
            );
        }

        return new JsonResponse($people);
    }

    /**
     * Get a Person entity.
     *
     * @Route("/person/{id}/", name="person_get", options={"expose" = true})
     * @Method("GET")
     */
    public function getPersonAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $po = $em->getRepository('AppBundle:Person')->find($id);

        $person = array(
            'id' => $po->getId(),
            'name' => $po->getName(),
            'emails' => $po->getEmails(),
            'phones' => $po->getPhones(),
            'addresses' => $po->getAddresses(),
            'createdAt' => $po->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $po->getUpdatedAt()->format('Y-m-d H:i:s'),
        );

        return new JsonResponse($person);
    }

    /**
     * Lists all Person entities.
     *
     * @Route("/person/", name="person_list", options={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        return array(
            'menu' => 'people'
        );
    }


    /**
     * Lists all Person entities for admins.
     *
     * @Route("/admin/person/", name="person_list_admin", options={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function adminListAction()
    {
        return array(
            'menu' => 'admin'
        );
    }

    /**
     * Displays a form to create a new Person entity.
     *
     * @Route("/admin/person/new/", name="person_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Person();

        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'menu' => 'admin'
        );
    }

    /**
     * Saves a Person entity.
     *
     * @Route("/admin/person/save/", name="person_save", options={"expose" = true})
     * @Method("POST")
     */
    public function saveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('id');
        $name = $request->get('name');
        $emails = $request->get('email');
        $phones = $request->get('phone');
        $addresses = $request->get('address');
        $date = new \DateTime();


        $person = new Person();
        if ($id) {
            $person = $em->getRepository('AppBundle:Person')->find($id);
        }
        else {
            $person->setCreatedAt($date);
        }
        $person->setName($name);
        $person->setEmails($emails);
        $person->setPhones($phones);
        $person->setAddresses($addresses);
        $person->setUpdatedAt($date);

        $em->persist($person);
        $em->flush();

        return new JsonResponse($person);
    }

    /**
     * Creates a form to create a Person entity.
     *
     * @param Person $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Person $entity)
    {
        $form = $this->createForm(new PersonType(), $entity, array());

        $label = $this->get('translator')->trans('app_bundle.label.create');
        $form->add('submit', 'submit', array('label' => $label, 'attr' => array('class' => 'btn-success')));

        return $form;
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/admin/person/edit/{id}/", name="person_edit", options={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $person = new Person();
        $editForm = $this->createEditForm($person);

        return array(
            'person' => $person,
            'form' => $editForm->createView(),
            'menu' => 'admin'
        );
    }

    /**
    * Creates a form to edit a Person entity.
    *
    * @param Person $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Person $entity)
    {
        $form = $this->createForm(new PersonType(), $entity, array());

        $label = $this->get('translator')->trans('app_bundle.label.update');
        $form->add('submit', 'submit', array('label' => $label, 'attr' => array('class' => 'btn-success')));

        return $form;
    }

    /**
     * Deletes a Person entity.
     *
     * @Route("/admin/person/{id}/", name="person_delete", options={"expose" = true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Person')->find($id);

        $em->remove($entity);
        $em->flush();

        return new JsonResponse($id);
    }
}
