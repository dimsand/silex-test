<?php
namespace MyApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MyClassController implements ControllerProviderInterface {

  public function connect(Application $app) {
    return $app['controllers_factory'];
  }

  public function home(Application $app, Request $request){
    $data = array(
        'name' => 'Your name'
    );

    $form = $app['form.factory']->createBuilder(FormType::class, $data)
        ->add('name', TextType::class, ['label'=>false, 'attr'=>array('placeholder'=>"Votre nom")])
        ->add('submit', SubmitType::class, [
            'label' => 'OK',
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();
        return $app->redirect($app['url_generator']->generate('hellopage', ['name'=>$data['name']]));
    }

    return $app['twig']->render('index.html.twig', array(
      'form' => $form->createView()
    ));
  }

  public function hello($name = "", Application $app){
    return $app['twig']->render('hello.html.twig', array(
        'name' => $name,
    ));
  }

}
