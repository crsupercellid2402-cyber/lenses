<?php

namespace App\Helpers;

class RxHelper
{
     /**
      * Транспозиция рецепта очковых линз.
      * @param float $sph
      * @param float $cyl
      * @param int $axis (0-180)
      * @return array [ 'sph' => float, 'cyl' => float, 'axis' => int ]
      */
     public static function transpose($sph, $cyl, $axis)
     {
          $newSph = round($sph + $cyl, 2);
          $newCyl = round(-$cyl, 2);
          if ($axis <= 90) {
               $newAxis = $axis + 90;
          } else {
               $newAxis = $axis - 90;
          }
          if ($newAxis > 180) $newAxis -= 180;
          if ($newAxis < 1) $newAxis += 180;
          return [
               'sph' => $newSph,
               'cyl' => $newCyl,
               'axis' => $newAxis
          ];
     }
}
