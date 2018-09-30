<?php
namespace App\Service;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Entity\Cleaner;
use App\Entity\Schedule;
use App\Entity\CleanUser;
use App\Repository\CleanerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateSchedule
{
    private $cleanUser;
    private $hours;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createSchedule($cleanuser, $hours)
    {

    $this->cleanUser = $cleanuser;
    $this->hours = $hours;

        $day = mt_rand(1, 7);
$begin = new DateTime('today + '.$day.' day');
$end;
if ($begin->format('l') === 'Sunday'){
    $begin = new DateTime('tomorrow + 1 day');
    $end = new DateTime('tomorrow + 1 day + 30 days');
}
else{
    $end = new DateTime('today + '.$day.' day + 30 days');
}


// Every week
$interval = new DateInterval( 'P1W' );
$daterange = new DatePeriod( $begin, $interval ,$end );

$random_time_numbers = [8, 10, 12, 2];
$k = array_rand($random_time_numbers);
$clean_time = $random_time_numbers[$k];

$schedule = [];

$cleaner = $this->entityManager->getRepository(Cleaner::class)->findCleaner(1);

if(null === $cleaner) {
    return false;
} else {

    foreach($daterange as $date){
        
        $scheduleObj = new Schedule;
        $scheduleObj->setCleaningDate($date);
        $scheduleObj->setCleaningTime($clean_time);
        $scheduleObj->setCleaningHours($hours);
        $scheduleObj->setCleanUser($cleanuser);
        $scheduleObj->setCleaner($cleaner);
    
        array_push($schedule, $scheduleObj);
       
    }
    
}

    return $schedule;

    }
}