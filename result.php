<?php
    if (isset($_POST["guess"]))
    {
        var_dump($_POST);

        $correct = false;

        if($_POST["guess"] == $_POST["correct_guess"])
        {
            $correct = true;
        }
    }



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
    if ($correct)
    {
        ?><h1>Correct!</h1> <?php
    }
    else
    {
        ?><h1>Sorry, try again.</h1> <?php
    }
?>

    <h2><?= $_POST["correct_name"] ?>'s <?= $_POST["ability_type"] ?> is</h2>
    <h2><?= $_POST["correct_guess"] ?></h2>


    <h3><a href="index.php">Play again.</a></h3>
</body>
</html>
