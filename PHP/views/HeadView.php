<?php
/*
 * Description of HeadView
 * This class is used to generate the HTML code for page head
 */

namespace PNM\views;

use \PNM\Request,
    \PNM\Config;

class HeadView
{

    const HEADERFULL = 0;
    const HEADERSLIM = 1;

    private static $headSent = false;
    private static $headerSent = false;

    public function render($header, $title = null)
    {
        $strippedTitle = strip_tags($title);
        if (self::$headSent == true) {
            return null;
        }
        ?><!DOCTYPE html>
        <!--[if lte IE 9]><html lang="en" class="old-ie"><![endif]-->
        <!--[if !IE]><!--><html lang="en"><!--<![endif]-->
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <meta name="description" content="An online database of ancient Egyptian personal names, titles, and persons from the Middle Kingdom.">
                <title><?= !empty($title) ? "$strippedTitle | " : "" ?>Persons and Names of the Middle Kingdom</title>
                                                                                        <!--<base href="<?= Config::BASE ?>">-->
                <meta property="og:title" content="<?= !empty($title) ? "$strippedTitle | " : "" ?>Persons and Names of the Middle Kingdom">
                <meta property="og:url" content="<?= Config::HOST . Request::stableURL() ?>">
                <meta property="og:type" content="website">
                <meta property="og:image" content="<?= Config::BASE ?>assets/favicon/favicon-32x32.png">
                <?=(Request::get('used_ver') < Request::maxVer()) ? '<meta name="robots" content="noindex">' : null ?>
                <link rel="canonical" href="<?= Config::HOST . Request::stableURL() ?>">
                <link rel="stylesheet" href="<?= Config::BASE ?>assets/style/style.css">
                <link rel="shortcut icon" href="<?= Config::BASE ?>assets/favicon/favicon.ico">
                <link rel="apple-touch-icon" sizes="180x180" href="<?= Config::BASE ?>assets/favicon/apple-touch-icon.png">
                <link rel="icon" type="image/png" sizes="32x32" href="<?= Config::BASE ?>assets/favicon/favicon-32x32.png">
                <link rel="icon" type="image/png" sizes="16x16" href="<?= Config::BASE ?>assets/favicon/favicon-16x16.png">
                <link rel="manifest" href="<?= Config::BASE ?>assets/favicon/site.webmanifest">
                <link rel="mask-icon" href="<?= Config::BASE ?>assets/favicon/safari-pinned-tab.svg" color="#a88338">
                <link rel="shortcut icon" href="<?= Config::BASE ?>assets/favicon/favicon.ico">
                <meta name="msapplication-TileColor" content="#2b5797">
                <meta name="msapplication-config" content="<?= Config::BASE ?>assets/favicon/browserconfig.xml">
                <meta name="theme-color" content="#ffffff">
                <!--[if lte IE 9]>
                    <script src="<?= Config::BASE ?>assets/script/classlist-polyfill.js"></script>
                    <script src="<?= Config::BASE ?>assets/script/html5shiv.min.js"></script>
                <![endif]-->
            </head>
            <?php
            self::$headSent = true;
            if (self::$headerSent == true) {
                return null;
            }
            ?>
            <body>
                <?php
                if ($header == self::HEADERFULL) {
                    require 'views/header-large.php';
                } elseif ($header == self::HEADERSLIM) {
                    require 'views/header.php';
                    if (!empty($title)) {
                        echo '<h1>', $title, '</h1>';
                    }
                }
                self::$headerSent = true;
            }
        }
        