<!DOCTYPE html>
<html lang="en" ng-app="skApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Šķūnenieku Kauss</title>
    <link href="<?php echo elixir('css/app.css'); ?>" rel="stylesheet"  media="screen">
</head>
<body ng-controller="MainController as mainCtrl">
    <div ng-include="'header.html'"></div>
    <div class="container" app-view-segment="0">
    </div>
    <script src="<?php echo elixir('js/bundle.js'); ?>"></script>
    <script>
        angular.module('AppConfig').config(function (AppConfigProvider) {
            AppConfigProvider.set({
                indActiveEventStart: '<?php echo $indActiveEventStart; ?>',
                mtbActiveEventStart: '<?php echo $mtbActiveEventStart; ?>',
            });
        });
    </script>
</body>
</html>
