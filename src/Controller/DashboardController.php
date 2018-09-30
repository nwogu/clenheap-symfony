<?php

namespace App\Controller;

use App\Entity\CleanUser;
use App\Service\CheckNumber;
use App\Service\CalculateRating;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request, UserInterface $user)
    {
        // $cleanuser = $this->getDoctrine()->getRepository('App:CleanUser')->find($id);

        if ($request->query->get('page') !== null && $request->query->get('page') === 'schedule') {
            return $this->render('dashboard/schedule.html.twig', [
                    'user' => $user,
                ]);
        } elseif ($request->query->get('page') !== null && $request->query->get('page') === 'cleaners') {
            return $this->render('dashboard/cleaners.html.twig', [
                'user' => $user,
            ]);
        } elseif ($request->query->get('page') !== null && $request->query->get('page') === 'plan') {
            return $this->render('dashboard/plan.html.twig', [
                'user' => $user,
            ]);
        } elseif ($request->query->get('page') !== null && $request->query->get('page') === 'profile') {
            return $this->render('dashboard/profile.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/dashboard/edit-profile", name="edit-profile")
     */
    public function editProfile(Request $request, UserInterface $user, CheckNumber $checknumber)
    {
        if ($request->request->has('phone') && $request->request->has('address')) {
            $phone = $request->request->get('phone');
            $address = $request->request->get('address');
            $first = $request->request->get('first');
            $last = $request->request->get('last');
            if (empty($phone) || empty($address) || empty($first) || empty($last)) {
                $this->addFlash(
                    'error',
                    'Please Fill All Fields'
                );
            } elseif ($checknumber->checkNumber($phone)) {
                $user->setPhone($phone);
                $user->setAddress($address);
                $user->setFirstName($first);
                $user->setLastName($last);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Profile Updated Successfully'
                );
            } else {
                $this->addFlash(
                    'error',
                    'The phone number is not a valid Nigerian number'
                );
            }
        } else {
            $this->addFlash(
                'error',
                'Invalid Details'
            );
        }

        return $this->redirectToRoute('dashboard', array('page' => 'profile'));
    }

    /**
     * @Route("/dashboard/change-plan", name="change-plan")
     */
    public function changePlan(Request $request, UserInterface $user)
    {
        if ($request->request->has('plan')) {
            $plan = $request->request->get('plan');

            if ($plan == 'small' && $user->getPlan() !== 'small') {
                $user->setPlan('small');
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Plan Changed Successfully. Effective After Your Last Schedule'
                );
            } elseif ($plan == 'medium' && $user->getPlan() != 'medium') {
                $user->setPlan('medium');
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Plan Changed Successfully. Effective After Your Last Schedule'
                );
            } elseif ($plan == 'large' && $user->getPlan() !== 'large') {
                $user->setPlan('large');
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Plan Changed Successfully. Effective After Your Last Schedule'
                );
            } else {
                $this->addFlash(
                    'error',
                    'You can\'t switch to the same plan'
                );
            }
        } else {
            $this->addFlash(
                'error',
                'Invalid Details'
            );
        }

        return $this->redirectToRoute('dashboard', array('page' => 'plan'));
    }

    /**
     * @Route("/dashboard/manage-plan", name="manage-plan")
     */
    public function managePlan(Request $request, UserInterface $user)
    {
        if ($request->request->has('plan') && $request->request->get('submit') == 'activate') {
            $plan = $request->request->get('plan');

            if ($plan == 'small' && $user->getPlan() !== 'small' && $user->getIsActive() !== true) {
                $user->setPlan('small');
                $user->setIsActive(true);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Plan Activated Successfully'
                );
            } elseif ($plan == 'medium' && $user->getPlan() != 'medium' && $user->getIsActive() !== true) {
                $user->setPlan('medium');
                $user->setIsActive(true);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Plan Activated Successfully'
                );
            } elseif ($plan == 'large' && $user->getPlan() !== 'large' && $user->getIsActive() !== true) {
                $user->setPlan('large');
                $user->setIsActive(true);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Plan Activated Successfully'
                );
            } elseif ($user->getIsActive() == true) {
                $this->addFlash(
                    'error',
                    'You can\'t activate an already existing active plan'
                );
            } else {
                $user->setIsActive(true);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'error',
                    'Plan Activated Successfully'
                );
            }
        } elseif ($request->request->get('submit') == 'pause') {
            $user->setIsActive(false);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'error',
                'Your plan has been paused succesfully. You can activate it at any time'
            );
        } else {
            $this->addFlash(
                'error',
                'Invalid Details'
            );
        }

        return $this->redirectToRoute('dashboard', array('page' => 'plan'));
    }

    /**
     * @Route("/dashboard/rate-cleaner", name="rate-cleaner")
     */
    public function rateCleaner(Request $request, UserInterface $user, CalculateRating $calc)
    {
        if ($request->request->has('rate-cleaner')) {
            $rating = $request->request->get('rate-cleaner');

            $new_rating = $calc->calculateRating($rating, $user->getCleaner());
            $new_count = $user->getCleaner()->getCountRating() + 1;
            $user->getCleaner()->setRating($new_rating);
            $user->getCleaner()->setCountRating($new_count);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'error',
                'Rating Submited'
            );
        } else {
            $this->addFlash(
                'error',
                'Invalid Details'
            );
        }

        return $this->redirectToRoute('dashboard', array('page' => 'cleaners'));
    }

    /**
     * @Route("/dashboard/schedule/{id}", name="manage-schedule")
     */
    public function scheduleManager($id, Request $request, UserInterface $user)
    {
        return $this->render('dashboard/manage_schedule/manage.html.twig', [
            'user' => $user,
        ]);
    }
}
