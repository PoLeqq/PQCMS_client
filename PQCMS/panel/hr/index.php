<?php

    session_start();
    if(empty($_SESSION["pqcms-panel-username"]))
    {
        header("location: ../");
        die("Najpierw musisz się zalogować!");
    }

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQCMS - HR</title>

    <link rel="stylesheet" href="../../bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../default.css">
    <link rel="stylesheet" href="index.css">

    <script src="hr.js" type="module" defer></script>
</head>
<body>
    <div id="overlay">
        <iframe></iframe>
    </div>
    <!-- <i>Jeżeli chcesz ustawić pole na puste, po prostu nic nie wpisuj.</i> -->
    <div class="d-flex" id="main">
        <div class="col-2 disableSelect" id="hrNav">
            <ul>
                <li class="expand">
                    <div class="liManageable">
                        <div class="col-10 d-flex align-items-center">
                            <div class="col-2 ulArrow" rotate="true">
                                <img src="images/arrow.svg">
                            </div>
                            <div class="ulText">
                                Użytkownicy
                            </div>
                        </div>
                        <div class="col-2 ulPlus">
                            <img class="overlayLink" overlayPath="./overlays/users/" src="images/plus.svg">
                        </div>
                    </div>
                    <ul class="px-4 ulHideable">
                        <?php

                        //                        TODO Connector may change this code
//                        require_once("../../hr/User.php");
                        //                        foreach(getAllUsers("priority",false) as $user)
                        //                            echo "<li>$user->nickname</li>";

                        require_once(dirname(__DIR__,2)."/Communicator.inc.php");
                        $users = Communicator::communicate(CommunicateURL::GET_USER,["admin" => 0])["resp"];

                        foreach($users as $user)
                        {
                            echo<<<HTML
<li>
    <b>${user["nickname"]}</b>
    (${user["username"]})
</li>
HTML;
                        }

                        ?>
                    </ul>
                </li>
                <li class="expand">
                    <div class="liManageable">
                        <div class="col-10 d-flex align-items-center">
                            <div class="col-2 ulArrow" rotate="true">
                                <img src="images/arrow.svg" alt="arrow">
                            </div>
                            <div class="ulText">
                                Rangi
                            </div>
                        </div>
                        <div class="col-2 ulPlus">
                            <img class="overlayLink" overlayPath="./overlays/ranks/" src="images/plus.svg">
                        </div>
                    </div>
                    <ul class="px-4 ulHideable">
                    <?php
                    //                        TODO Connector may change this code
                        require_once("../../hr/Rank.php");
//                            foreach(getAllRanksOrder("priority",false) as $rank)
//                                echo "<li>$rank->name</li>";
                    ?>
                    </ul>
                </li>
                <li class="expand">
                    <div class="liManageable">
                        <div class="col-10 d-flex align-items-center">
                            <div class="col-2 ulArrow" rotate="true">
                                <img src="images/arrow.svg">
                            </div>
                            <div class="ulText">
                                Permisje
                            </div>
                        </div>
                        <div class="col-2 ulPlus">
                            <img src="images/plus.svg">
                        </div>
                    </div>
                    <ul class="px-4 ulHideable">
                        <li>Informacje</li>
                        <li>Domyślne</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="row col-9">
            <div>Tutaj np. jak kliknie na jakąś rangę/usera to coś tam może mu edytować</div>
        </div>
    </div>
</form>
</body>
</html>