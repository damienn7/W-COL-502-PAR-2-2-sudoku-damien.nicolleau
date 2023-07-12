<?php

//getting args
if (count($argv) == 3) {
    $lGrid = intval($argv[1]);
    $filename = $argv[2];
    $grid = @set_grid($filename, $lGrid);
    if (count($grid) == $lGrid * $lGrid) {
        $grid = @solve_sudoku($grid, $lGrid);
    } else {
        echo "Erreur - La grille et la taille spécifiée sont incorrectes!\n";
        return;
    }

    // var_dump($resultValue);
    $resultGrid = "";
    foreach ($grid as $key => $value) {
        array_push($value, "\n");
        foreach ($value as $key => $char) {
            $resultGrid .= $char;
        }
    }

    echo $resultGrid . "\n";
} else {
    echo "Erreur - trop ou pas assez d'arguments passés en paramètre du programme!\n";
}

function set_grid($f, $l)
{
    $grid = array();

    $content = file_get_contents($f);

    if ($content === false) {
        echo ("Erreur - Impossible de lire le fichier spécifié en argument!\n");
    }

    $lRow = $l * $l;
    $grid = str_split(str_replace("\n", "", $content), $lRow);

    foreach ($grid as $key => &$value) {
        $value = str_split($value);
    }

    return $grid;
}

function solve_sudoku($g, $l, $completedGrid = false, $oldX = 0, $oldY = 0, $i = 0, $listNumToSkip = array())
{

    for ($y = 0; $y < $l * $l; $y++) {
        for ($x = 0; $x < $l * $l; $x++) {
            if ($g[$y][$x] == ".") {
                $oldY = $y;
                $oldX = $x;
                echo $g[$y][$x];
                $solution = test_numbers($g, $y, $x, $l * $l, $listNumToSkip);
                echo $g[$y][$x];
                // var_dump($g);
                // die();
                if (count($solution) == 9) {
                    echo "Erreur\n : pas de solution\n";
                    // array_push($listNumToSkip, $g[$y][$x]);
                    // var_dump($listNumToSkip);
                    // die();
                    // $g[$y][$x] = ".";
                    // solve_sudoku($g, $l, false, $oldX, $oldY, $i,);
                } else {
                    $listNumToSkip = array();
                }
            }
        }
    }

    return $g;
}

function test_numbers(&$g, $y, $x, $rowL, $skip)
{
    $possibleValues = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
    $acceptedValues = array();
    $notAcceptedValues = array();
    // echo $rowL;
    // foreach ($possibleValues as $key => $value) {
    for ($i = 0; $i < $rowL; $i++) {
        if ($g[$y][$i] != ".") {
            array_push($notAcceptedValues, $g[$y][$i]);
        }

        if ($g[$i][$x] != ".") {
            array_push($notAcceptedValues, $g[$i][$x]);
        }
    }
    // }
    foreach ($possibleValues as $key => $value) {
        if (count($skip) === 0) {
            if (!is_int(array_search($value, $notAcceptedValues))) {
                array_push($acceptedValues, $value);
            }
        } else {
            if (!is_int(array_search($value, $notAcceptedValues))&&!is_int(array_search($value,$skip))) {
                array_push($acceptedValues, $value);
            }
        }
    }


    // var_dump(array_unique($skip));
    
    $g[$y][$x] = $acceptedValues[0];
    return $notAcceptedValues;
}
