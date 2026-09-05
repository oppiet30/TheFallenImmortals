<?php

declare(strict_types=1);

$dbaBootstrap = __DIR__ . '/phpstan-dba-bootstrap.php';
if (is_file($dbaBootstrap)) {
    require_once $dbaBootstrap;
}