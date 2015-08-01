<?php

function adminer_object() {
    class AdminerSoftware extends Adminer {
        function credentials() {
            return [
                config('database.connections.mysql.host'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
            ];
        }
    }

    return new AdminerSoftware;
}
