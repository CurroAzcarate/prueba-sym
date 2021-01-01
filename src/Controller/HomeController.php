<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            /* pasa variables a la vista*/
            'Hello'=>'Hola Mundo con symfony 4'
        ]);
    }
    public function animales($nombre,$apellidos)
    {
        $title='Bienvenido a la página de animales';
        $animales= array('perro','gato','paloma','rata');
        $aves= array(
            'tipo'=>'palomo',
            'color'=>'gris',
            'edad'=>4,
            'raza'=>'colillano'
            );
        /*devolver una vista*/
        return $this->render('home/animales.html.twig',[
            'title'=>$title,
            'nombre'=>$nombre,
            'apellidos'=>$apellidos,
            'animales'=>$animales,
            'aves'=>$aves
                
                
        ]);
    }
    
    public function redirigir(){
//        return $this->redirectToRoute('animales',[
//            'nombre' => 'Lola' ,
//            'apellidos' => 'Flores'
//        ]);
        return $this->redirect('https://www.google.es');
    }
}

