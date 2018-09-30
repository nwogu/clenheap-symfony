<?php

namespace App\Controller;


use App\Entity\CleanUser;
use App\Service\CheckNumber;
use App\Service\CreateSchedule;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class HomePageController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
        return $this->render('home_page/index.html.twig');
    }

     /**
     * @Route("/subscribe", name="subscribe")
     */
    public function subscribe(
        Request $request,
        CheckNumber $checkNumber,
        CreateSchedule $createSchedule,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator
    ) {
        $cleanuser = new CleanUser;

        if ($request->query->get('plan') !== null && $request->query->get('plan') === 'large'){

            $plan = 'large';
            $hours = 7;      
        }elseif ($request->query->get('plan') !== null && $request->query->get('plan') === 'medium'){

            $plan = 'medium';
            $hours = 5;  
        }else{

            $plan = 'small';
            $hours = 3;  

        }
        

        $cleanuser->setPlan($plan);
        $cleanuser->setIsActive(true);

        $form = $this->createFormBuilder($cleanuser)
        ->add('useremail', EmailType::class, array('label'=> 'What\'s your email?','attr'=> array('class'=> 'form-control form-inline', 'style' => 'margin-bottom:20px')))
        ->add('first_name', TextType::class, array('label'=> 'What\'s your first name?','attr'=> array('class'=> 'form-control form-inline', 'style' => 'margin-bottom:20px')))
        ->add('last_name', TextType::class, array('label'=> 'What\'s your last name?','attr'=> array('class'=> 'form-control form-inline', 'style' => 'margin-bottom:20px')))
        ->add('address', TextType::class, array('label'=> 'What\'s your Address?','attr'=> array('class'=> 'form-control form-inline', 'style' => 'margin-bottom:20px')))
        ->add('phone', TextType::class, array('label'=> 'What\'s your phone number?','attr'=> array('class'=> 'form-control form-inline', 'style' => 'margin-bottom:20px')))
        ->add('gender', ChoiceType::class, array('choices' => array('Male'=>'Male', 'Female'=>'Female'), 'attr'=> array('class'=> 'form-control', 'style' => 'margin-bottom:20px')))
        ->add('password', PasswordType::class, array('label'=> 'Choose a password','attr'=> array('class'=> 'form-control form-inline', 'style' => 'margin-bottom:20px')))
        ->add('confirmPassword', PasswordType::class, array('label'=> 'Confirm pasword','attr'=> array('class'=> 'form-control form-inline', 'style' => 'margin-bottom:20px')))
        ->add('save', SubmitType::class, array('label'=> 'Subscribe', 'attr'=> array('class'=> 'btn btn-primary', 'style' => 'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            $errors = $validator->validate($cleanuser);
            $phone = $form['phone']->getData();
            $password = $form['password']->getData();
            $confirm = $form['confirmPassword']->getData();
            $isValid = $checkNumber->checkNumber($phone);

            if (count($errors) > 0) {

                die();
            }
            elseif($isValid === false){
                $this->addFlash(
                    'error',
                    'The phone number you supplied, '.$phone.' is not a valid Nigerian number'
                );
    
                return $this->redirectToRoute('subscribe', array('plan' => $cleanuser->getPlan()));
            }
            elseif($password !== $confirm){
                $this->addFlash(
                    'error',
                    'Your Password does not match'
                );
    
                return $this->redirectToRoute('subscribe', array('plan' => $cleanuser->getPlan()));
            }else{
                $password = $passwordEncoder->encodePassword($cleanuser, $confirm);
                $cleanuser->setPassword($password);
            }
            // $name = $form['name']->getData();
            // $category = $form['category']->getData();
            // $description = $form['description']->getData();
            // $priority = $form['priority']->getData();
            // $due_date = $form['due_date']->getData();

            $now = new\DateTime('now');

            // $cleanuser->setName($name);
            // $cleanuser->setCategory($category);
            // $cleanuser->setDescription($description);
            // $cleanuser->setPriority($priority);
            // $cleanuser->setDueDate($due_date);
            $cleanuser->setCreatedAt($now);

            $em = $this->getDoctrine()->getManager();

            $scheduleList = $createSchedule->createSchedule($cleanuser, $hours);
            $cleanuser->setCleaner($scheduleList[0]->getCleaner());
            $em->persist($cleanuser);
            foreach ($scheduleList as $schedules){
                $em->persist($schedules);
            }
            $em->flush();

            $token = new UsernamePasswordToken($cleanuser, null, 'main', $cleanuser->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));
            return $this->redirectToRoute('dashboard');

            }
        
        return $this->render('home_page/subscribe.html.twig', array('form'=> $form->createView()));
    }

    
}
