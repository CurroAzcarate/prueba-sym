<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Animal;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Form\AnimalType;
class AnimalController extends AbstractController
{
    /**
     * @Route("/animal", name="animal")
     */
    
    public function validarEmail($email) {
        $validator=Validation::createValidator();
        $errores=$validator->validate($email, [
            new Email()
        ]);
        if(count($errores)!=0){
            echo 'El dato no se ha validado';
        }else{
            echo 'Email correcto';
        }
        die();
    }
    public function index()
    {
        $em=$this->getDoctrine()->getManager();
        $animal_repo=$this->getDoctrine()->getRepository(Animal::class);
        $animales=$animal_repo->findAll();
        
        
        
        //query builder
        $qb=$animal_repo->createQueryBuilder('a')
                ->andWhere("a.raza= :raza")
                ->setParameter('raza','Africana')
                ->orderBy('a.id' ,'DESC')
                ->getQuery();
        
        $resulset=$qb->execute();

        
        var_dump($resulset);
        
        //DQL
//        $dql="SELECT a FROM App\Entity\Animal a WHERE a.raza='Africana'";
//        $query=$em->createQuery($dql);
//        
//        $resulset=$query()->execute();
//
//        
//        var_dump($resulset);
        
        //
        //SQL
        $connection=$this->getDoctrine()->getConnection();
        $sql="SELECT * from animal ORDER BY id DESC";
        
        $prepare=$connection->prepare($sql);
        $prepare->execute();
        $resulset=$prepare->fetch();
        var_dump($resulset);
        
        ///1
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales'=>$animales
        ]);
    }
    
    public function save(){
        
        
        //guardar en una tabla de la base de datos
        
        //cargo el em
        $entityManager=$this->getDoctrine()->getManager();
        //creo el objeto y le doy valores
        $animal=new Animal();
        $animal->setTipo('Avestruz');
        $animal->setColor('Verde');
        $animal->setRaza('Africana');
        
        //persistir el objeto en doctrine, guardarlo
        $entityManager->persist($animal);
        
        //volcar datos en la tabla
        $entityManager->flush();
        
        return new Response("El animal guardado tiene el id ".$animal->getId());
         
    }
    
    public function animal($id){
        
        //cargar repositorio
        $animal_repo=$this->getDoctrine()->getRepository(Animal::class);
        
        //hacer consulta
        
        $animal=$animal_repo->find($id);
        
        if(!$animal){
            $message='animal no encontrado';
        }else{
            $message='tu animal elegido es :'.$animal->getTipo().' es de color '.$animal->getColor().' y es de la raza '.$animal->getRaza();
        }
        return new Response($message);
    }
    
    public function update($id){
        //cargar doctrine
        $doctrine=$this->getDoctrine();
        //cargar entityManager
        $em=$doctrine->getManager();
        
        //cargar repo animal
        
        $animal_repo=$em->getRepository(Animal::class);
        //find para conseguir el objeto
        //
        $animal=$animal_repo->find($id);
        //comprobar si el objeto  me llega
        
        if(!$animal){
            $message='animal no encontrado';
        }else{
        
            //asignar valores al objeto
            $animal->setTipo('Perro');
            $animal->setColor('Naranja');
            $animal->setRaza('Pastor');
            //persistir en doctrine
            $em->persist($animal);
            //volcar datos en la  bd
            $em->flush();
            //respuesta
            $message='Datos de animal '.$animal->getId().'actualizados a'.$animal->getTipo().' - '.$animal->getColor().' - '.$animal->getRaza();
            
        }
        return new Response($message);
    }
    
    public function delete(Animal $animal){
        
        
        if($animal){
        //Cargar EntityManager
           $em=$this->getDoctrine()->getManager();
           //eliminar
           
           $em->remove($animal);
           $em->flush();
           //mensaje
            $message="Borrado";

            
        }else{
            $message="El animal no existe";
        }
        return new Response($message);
    }
    //crear formulario
    public function  crearAnimal(Request $request){
        $animal=new Animal();
        $form=$this->createForm(AnimalType::class,$animal);
        
        $form->handleRequest($request);//recoge datos del formulario
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();
            
            //session flush
            
            $session =new Session();
          
            $session->getFlashBag()->add('message','animal creado');
            
            return $this->redirectToRoute('crear_animal');
        }
        
        return $this->render('animal/crear-animal.html.twig',[
            'form' => $form->createView()
                
        ]);
        
    }
}
