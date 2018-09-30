<?php

namespace App\Service;

class CalculateRating
{
    public function calculateRating($rating, $user)
    {
        $count = $user->getCountRating();
        $current_rating = $user->getRating();
        $total_ratings = $current_rating * $count;
        $new_total_rating = $rating + $total_ratings;
        $new_rating = round($new_total_rating / ($count + 1), 1);

        return $new_rating;
    }
}
