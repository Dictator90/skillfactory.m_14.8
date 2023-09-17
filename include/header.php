<?php if (!defined('PROLOG_INCLUDED') || PROLOG_INCLUDED !== true) die(); ?>

<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title><?=$title?></title>
<?php if ($incCss): ?>
    <link rel="stylesheet" href="/assets/css/style.css?<?=filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/css/style.css')?>">
<?php endif; ?>
<?php if ($incJs): ?>
    <script type="application/javascript" src="/assets/js/script.js?<?=filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/js/script.js')?>"></script>
<?php endif; ?>
