
<?php

define('STR_TEXTE',   'texte');
define('STR_ACTION',  'action');

$bool_action = isset($_REQUEST[STR_ACTION]) && isset($_REQUEST[STR_TEXTE]);

$str_texte    = '';
$str_formater = '';

if ($bool_action)
{
    $str_texte = $_REQUEST[STR_TEXTE];
    if (get_magic_quotes_gpc())
    {
        $str_texte = stripslashes($str_texte);
    }
}

function tester_password($mdp)
{
    $longueur = strlen($mdp);
    for($i = 0; $i < $longueur; $i++)
    {
        $lettre = $mdp[$i];

        if ($lettre >= 'a' && $lettre <= 'z')
        {
            $point += 1;
            $point_min = 1;
        }
        else if ($lettre >= 'A' && $lettre <= 'Z')
        {
            $point += 2;
            $point_maj = 2;
        }
        else if ($lettre >= '0' && $lettre <= '9')
        {
            $point += 3;
            $point_chiffre = 3;
        }
        else
        {
            $point += 5;
            $point_caracteres = 5;
        }
    }

    // Calcul du coefficient points/longueur
    $etape1 = $point / $longueur;

    // Calcul du coefficient de la diversité des types de caractères...
    $etape2 = $point_min + $point_maj + $point_chiffre + $point_caracteres;

    // Multiplication du coefficient de diversité avec celui de la longueur
    $resultat = $etape1 * $etape2;

    // Multiplication du résultat par la longueur de la chaîne
    $final = $resultat * $longueur;

    return $final;
}

function tester_hughes($mdp)
{
    $longueur = strlen($mdp);

    $coeff_minuscule = $coeff_majuscule = $coeff_chiffre = $coeff_ponctuation = $coeff_autre = 0;

    $force = 0.0;

    for($ii = 0; $ii < $longueur; $ii++)
    {
        $lettre = $mdp[$ii];
        if (ord($lettre) >= ord('a') && ord($lettre) <= ord('z'))
        {
            $force += 26;
            if (!$coeff_minuscule) { $coeff_minuscule = 0.1; }
        }
        else if (ord($lettre) >= ord('A') && ord($lettre) <= ord('Z'))
        {
            $force += 26;
            if (!$coeff_majuscule) { $coeff_majuscule = 0.1; }
        }
        else if (ord($lettre) >= ord('0') && ord($lettre) <= ord('9'))
        {
            $force += 10;
            if (!$coeff_chiffre) { $coeff_chiffre = 0.1; }
        }
        else if (strpos('!"#$%&\'()*+,-./:;<=>@[\]^_`{|}~', $lettre) !== FALSE)
        {
            $force += 32;
            if (!$coeff_ponctuation) { $coeff_ponctuation = 0.1; }
        }
        else
        {
            $force += 128;
            if ($coeff_autre == 0) { $coeff_autre = 0.1; }
        }
    }

    $coeff = 1.0 + $coeff_minuscule + $coeff_majuscule + $coeff_chiffre + $coeff_ponctuation + $coeff_autre;

    $force *= $coeff;

    return intval($force);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <title>Tester la force d'un mot de passe</title>
        <style type="text/css">
        <!--

        body *
        {
            font-family: monospace;
            font-size: 8pt;
        }

        input
        {
            display: block;
        }

        input.text
        {
            width: 800px;
        }

        form
        {
            margin: 0;
        }

        -->
        </style>
        <script type="text/javascript">
        <!--;
            function on_load()
            {
                if (document && document.getElementById)
                {
                    var obj_pattern = document.getElementById('<?php print STR_TEXTE; ?>');
                    if (obj_pattern && obj_pattern.focus)
                    {
                        obj_pattern.focus();
                    }
                }
            }

        //-->
        </script>
    </head>
    <body onload="on_load();">
        <form method="post">
            <input type="hidden" name="<?php echo STR_ACTION; ?>" value="<?php echo STR_ACTION; ?>" />
            <input class="text" type="text" name="<?php echo STR_TEXTE; ?>" value="<?php echo $str_texte; ?>" />
            <input type="submit" class="submit" value="tester" />
        </form>
<?php
if ($bool_action)
{
    $int_password = tester_password($str_texte);
    $int_hughes   = tester_hughes($str_texte);
    $int_moyenne  = intval(($int_password + $int_hughes) / 2.0);
    echo '<table>';
    echo '<tr><td>Vu sur le net</td><td>'.$int_password.'</td></tr>';
    echo '<tr><td>Hughes</td><td>'.$int_hughes.'</td></tr>';
    echo '<tr><td>Moyenne</td><td>'.$int_moyenne.'</td></tr>';
    echo '</table>';
}

echo '<hr />';
highlight_file(__FILE__);

?>
    </body>
</html> 