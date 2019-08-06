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

        .break {
            page-break-after: always;
        }

        @page
        {
            /*margin-top: 58mm; <- 2017 */
            /*margin-top: 138mm; <- 2018 */
            margin-top: 138mm; /* <- todo 2019 /*
            margin-left: 8mm;
        }

        body {
            /*margin: 100mm;*/
        }

        .diploma {
            text-align: center;
        }

        h3 {
            font-weight: 400;
            line-height: 0;
        }

        .name h3 {
            margin-top: 40px;
        }

        .place h3 {
            margin-top: 67px;
        }

        .group h3 {
            margin-top: 66px;
        }

        .result h3 {
            margin-top: 70px;
        }

        .description {
            font-size: 10px;
            margin-top: 17px;
        }
    </style>
</head>
<body>
    <?php foreach ($diplomas as $group): ?>
        <?php foreach ($group as $diploma): ?>
            <?php if ($diploma->group === 'K') { ?>
                <div class="diploma break">
                    <div class="name">
                        <h3><?=$diploma->nameInDative?></h3>
                    </div>
                    <div class="place">
                        <h3>(komanda "<?=$diploma->teamName?>")</h3>
                    </div>
                    <div class="group">
                        <h3>par izcīnīto <?=$diploma->place?>. vietu</h3>
                    </div>
                    <div class="result">
                        <h3>triatlona stafetē (0.2+7+2)</h3>
                    </div>
                    <div class="description">
                        &nbsp;
                    </div>
                </div>
            <?php } else { ?>
                <div class="diploma break">
                    <div class="name">
                        <h3><?=$diploma->nameInDative?></h3>
                    </div>
                    <div class="place">
                        <h3>par izcīnīto <?=$diploma->place?>. vietu</h3>
                    </div>
                    <div class="group">
                        <h3>triatlonā <?=$diploma->group?> grupā</h3>
                    </div>
                    <div class="result">
                        <h3>ar rezultātu <?=$diploma->result?></h3>
                    </div>
                    <div class="description">
                        <?=$diploma->group?> - <?=$phrases[$diploma->group]?>
                    </div>
                </div>
            <?php } ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</body>
</html>
