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
        <?php echo file_get_contents(base_path().'/public'.elixir('css/app.css')); ?>

        .header {
            background-color: #C0C0C0;
        }

        h1 {
            font-size: 26px;
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
                <p><?=$phrases['eventDate']?></p>
                <p><?=$phrases['eventDescription']?></p>
                <p><?=$phrases['eventDistanceDescription']?></p>
            </div>
        </div>
        <?php foreach ($results as $gender => $groups): ?>
            <div class="row">
                <div class="col-xs-12">
                    <h4><?=$phrases[$gender.'G']?></h4>
                </div>
            </div>
            <div class="row">
                <?php foreach ($groups as $group => $individualIds): ?>
                    <div class="col-xs-12">
                        <hr>
                        <table class="table">
                            <caption>
                                Starta grupa <strong><?=$group?></strong> - <?=$phrases[$group]?>
                            </caption>
                            <thead>
                                <tr>
                                    <th>Vieta</th>
                                    <th>#</th>
                                    <th><?=$phrases[$gender.'Participant']?></th>
                                    <?php if ($group !== 'K'): ?>
                                        <th>Dz gads</th>
                                    <?php endif; ?>
                                    <th>Starts</th>
                                    <th>Finišs</th>
                                    <th>Rezultāts</th>
                                    <?php if ($group === 'K'): ?>
                                        <th>Dalībnieki</th>
                                    <?php endif; ?>
                                    <?php if ($group !== 'K'): ?>
                                        <th>Komentārs</th>
                                    <?php endif; ?>
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
                                            <?php if ($group !== 'K'): ?>
                                                <td><?=$individual->birthYear?></td>
                                            <?php endif; ?>
                                            <td><?=$individual->start?></td>
                                            <td><?=$individual->finish?></td>
                                            <th><?=$individual->result?></th>
                                            <?php if ($group === 'K'): ?>
                                                <td>
                                                    <?=$individual->swimmerName?><span class="pull-right"><?=$individual->swimmerBirthYear?></span><br/>
                                                    <?=$individual->bikerName?><span class="pull-right"><?=$individual->bikerBirthYear?></span><br/>
                                                    <?=$individual->runnerName?><span class="pull-right"><?=$individual->runnerBirthYear?></span>
                                                </td>
                                            <?php endif; ?>
                                            <?php if ($group !== 'K'): ?>
                                                <td><?=$individual->comment?></td>
                                            <?php endif; ?>
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
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
