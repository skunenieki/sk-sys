<!DOCTYPE html>
<html lang="en" ng-app="skApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Manager</title>
    <link href="<?php echo elixir('css/app.css'); ?>" rel="stylesheet">
</head>
<body ng-controller="MainController as mainCtrl">
    <div ng-include="'header.html'"></div>
    <div class="container" app-view-segment="0">
    </div>
    <script src="<?php echo elixir('js/bundle.js'); ?>"></script>
    <script>
        angular.module('AppConfig').config(function (AppConfigProvider) {
            AppConfigProvider.set({
                x: '<?php echo $x; ?>',
            });
        });
    </script>
</body>
</html>
