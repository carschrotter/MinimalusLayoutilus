<?php
namespace mnhcc\ml;
use mnhcc\ml\classes\Control\ControlIndex;
if (!\defined("\\mnhcc\\ml\\INDEX")) die();
$lang = ControlIndex::getLang();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <title>Minimalus Layoutilus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Michael Hegenbarth (carschrotter)">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css">
    <mnhccTemplate:include type="head" name="style" renderer="none" />
    <mnhccTemplate:include type="head" name="script" renderer="none" />
    <mnhccTemplate:include type="head" name="head" renderer="none" />
    <style>
        body    { padding-top: 70px; padding-bottom: 40px; }
        footer  { border-top: 1px solid #eee; padding: 20px 0; color: #777; }
    </style>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed"
                    data-toggle="collapse" data-target="#navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Minimalus Layoutilus</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="https://github.com/carschrotter/MinimalusLayoutilus">GitHub</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <mnhccTemplate:include type="system" name="message" renderType="html" />
    <mnhccTemplate:include type="component" name="content" renderType="html" />
    <hr>
    <footer>
        <p>&copy; <?= date('Y') ?> Michael Hegenbarth (carschrotter) &mdash; LGPL 2.1</p>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
