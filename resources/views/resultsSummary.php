<!DOCTYPE html>
<html lang="en" ng-app="skApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Šķūnenieku Kauss</title>
    <style type="text/css">
        <?php echo file_get_contents(base_path().'/public/css/app.css'); ?>

        .header {
            background-color: #C0C0C0;
        }

        h1 {
            font-size: 28px;
            color: white;
        }

        tr {
            page-break-inside: avoid;
            font-size: 12px;
        }

        h4 {
            text-align: center;
        }

        p {
            font-size: 13px;
            font-weight: bold;
            text-align: right;
            margin: 0;
        }

        table {
            margin-bottom: 0;
        }

        hr {
            margin-top: 0;
            margin-bottom: 10px;
            border-top: 4px solid #dddddd;
        }

        strong {
            margin-left: 15px;
        }

        caption {
            color: black;
            padding-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row header">
            <div class="col-xs-6">
                <h1>Šķūnenieku Kauss <?=$eventYear?></h1>
            </div>
            <div class="col-xs-6">
                <p><?=$eventYear?>. gada 11. augusts</p> <!-- todo -->
                <p>Riteņbraukšnas sacensību protokols</p>
                <p>Distance: Lejasciems-Sinole-Lejasciems (10km)</p>
            </div>
        </div>
        <?php foreach ($results as $gender => $individualIds): ?>
            <div class="row">
                <div class="col-xs-12">
                    <h4><?=$phrases[$gender.'K']?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <hr>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Vieta</th>
                                <th>#</th>
                                <th><?=$phrases[$gender.'Participant']?></th>
                                <th>Dz gads</th>
                                <th>Starts</th>
                                <th>Finišs</th>
                                <th>Sods</th>
                                <th>Rezultāts</th>
                                <th>Komentārs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($individualIds as $resultInSeconds): ?>
                                <?php
                                    $last = end($resultInSeconds);
                                ?>
                                <?php foreach ($resultInSeconds as $individual): ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=$individual->number?></td>
                                        <td><?=$individual->name?></td>
                                        <td><?=$individual->birthYear?></td>
                                        <td><?=$individual->start?></td>
                                        <td><?=$individual->finish?></td>
                                        <td><?=$individual->penalty?></td>
                                        <td><?=$individual->result?></td>
                                        <td><?=$individual->comment?></td>
                                    </tr>
                                    <?php
                                        if (count($resultInSeconds) > 1 && $individual === $last) {
                                            $i += count($resultInSeconds);
                                        }
                                        if (count($resultInSeconds) < 2) {
                                            $i++;
                                        }
                                    ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
