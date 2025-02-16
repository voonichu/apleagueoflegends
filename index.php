<?php
    session_start();
    
    // initialize session variables if not set
    if (!isset($_SESSION['score'])) {
        $_SESSION['score'] = 0;
        $_SESSION['questions_asked'] = 0;
    }

    $TOTAL_QUESTIONS = 10; // total number of questions in the quiz
    $first_load = true;
    $correct = false;

    if (isset($_POST["guess"])) {
        $first_load = false;
        $_SESSION['questions_asked']++;

        if($_POST["guess"] == $_POST["correct_guess"]) {
            $correct = true;
            $_SESSION['score']++;
        }
    }

    // check if quiz is complete
    $quiz_complete = $_SESSION['questions_asked'] >= $TOTAL_QUESTIONS;

    // reset quiz if it's complete and user wants to start over
    if (isset($_POST['restart'])) {
        $_SESSION['score'] = 0;
        $_SESSION['questions_asked'] = 0;
        $quiz_complete = false;
    }

    function read_file($filename)
    {
        $champion_type = array("MAGE", "ASSASSIN", "FIGHTER", "TANK", "SUPPORT", "MARKSMAN");
        shuffle($champion_type);
        $answer_type = array_pop($champion_type);

        $lines = file($filename);
        shuffle($lines);
        $choices = array();
        while(count($choices) < 5)
        {
            $line = array_pop($lines);
            list($champion, $type, $passive, $q, $w, $e, $r) = explode(";", $line);
            if (str_contains($type, "/"))
            {
                list($type1, $type2) = explode(" / ", $type);
            }
            else
            {
                $type1 = $type;
                $type2 = $type;
            }


            if($answer_type == $type1 || $answer_type == $type2)
            {
                $choices[] = $line;
            }
        }
        return $choices;
    }

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AP League of Legends</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    if (!$quiz_complete) {
        $choices = read_file("champion_info_modified.txt");
        $answer_index = rand(0, count($choices) - 1);
        $ability = rand(2, 6);
        $ability_choice = array("Champion", "Type", "Passive", "Q", "W", "E", "R");

        list($correct_champion, $correct_type, $correct_passive, $correct_q, $correct_w, $correct_e, $correct_r) = explode(";", $choices[$answer_index]);
        $correct_list = array($correct_champion, $correct_type, $correct_passive, $correct_q, $correct_w, $correct_e, $correct_r);
?>
        <h1>AP League of Legends</h1>
        <div class="score-info">
            <p>Question <?= $_SESSION['questions_asked'] + 1 ?> of <?= $TOTAL_QUESTIONS ?></p>
            <p>Current Score: <?= $_SESSION['score'] ?></p>
        </div>

        <h2>What is <?= $correct_champion ?>'s <?= $ability_choice[$ability] ?></h2>

        <form action="index.php" method="post">
            <ol id="choices">
                <?php
                foreach($choices as $choice) {
                    list($champion, $type, $passive, $q, $w, $e, $r) = explode(";", $choice);
                    $choice_list = array($champion, $type, $passive, $q, $w, $e, $r);
                    ?>
                <li>
                    <label>
                        <input type="radio" name="guess" value="<?= $choice_list[$ability] ?>"/> <?= $choice_list[$ability] ?>
                    </label>
                </li>
                <?php
                }
                ?>
            </ol>
            <input type="hidden" name="correct_guess" value="<?= $correct_list[$ability] ?>">
            <input type="hidden" name="correct_name" value="<?= $correct_list[0] ?>">
            <input type="hidden" name="ability_type" value="<?= $ability_choice[$ability] ?>">
            <input type="submit" value="Submit Answer">
        </form>

        <?php
        if (!$first_load) {
            if ($correct) {
                ?><h1 class="feedback correct">Correct!</h1><?php
            } else {
                ?><h1 class="feedback incorrect">Sorry, try again.</h1><?php
            }
            ?>
            <h2><?= $_POST["correct_name"] ?>'s <?= $_POST["ability_type"] ?> is</h2>
            <h2><?= $_POST["correct_guess"] ?></h2>
            <?php
        }
    } else {
        // show final score and restart button
        ?>
        <h1>Test Complete</h1>
        <div class="final-score">
            <h2>Your Final Score: <?= $_SESSION['score'] ?> out of <?= $TOTAL_QUESTIONS ?></h2>
            <p>Percentage: <?= round(($_SESSION['score'] / $TOTAL_QUESTIONS) * 100) ?>%</p>
            <form action="index.php" method="post">
                <input type="submit" name="restart" value="Start New Quiz">
            </form>
        </div>
        <?php
    }
    ?>
</body>
</html>
