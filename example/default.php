<?php

use Apache\VhostHelper\ApacheConf;
use Apache\VhostHelper\Directory;
use Apache\VhostHelper\VirtualHost;

require __DIR__ . '/../vendor/autoload.php';


// short nested method
$apache_config = new ApacheConf("Start comment", "Finish comment");
$apache_config
    ->addVirtualHost(
        (new VirtualHost("*", "443", "sitobellissimo.com ssl"))
            ->addDirective("ServerName", "sitobellissimo.com")
            ->addDirective("DocumentRoot", "/var/www/sitobellissimo.com/public_html")
            ->addBreak()
            ->addDirectory(
                (new Directory("/var/www/sitobellissimo.com/public_html"))
                    ->addDirective("Options", "FollowSymLinks")
                    ->addDirective("AllowOverride", "All")
                    ->addDirective("Require", "all granted")
            )
            ->addBreak()
            ->enableSSL(
                "/path/to/sitobellissimo_com.crt",
                "/path/to/sitobellissimo_com.key",
                "/path/to/sitobellissimo_com.ca-bundle"
            )
            ->addBreak()
            ->addDirective("ErrorLog", "/var/www/sitobellissimo.com/logs/error.log")
            ->addDirective("LogLevel", "warn")
            ->addDirective("CustomLog", "/var/www/sitobellissimo.com/logs/access.log combined")
    )
    ->addVirtualHost(
        (new VirtualHost("*", "443", "www.sitobellissimo.com ssl REDIRECT"))
            ->addDirective("ServerName", "www.sitobellissimo.com")
            ->addBreak()
            ->enableSSL(
                "/path/to/sitobellissimo_com.crt",
                "/path/to/sitobellissimo_com.key",
                "/path/to/sitobellissimo_com.ca-bundle"
            )
            ->addBreak()
            ->redirect("301", "/", " https://www.sitobellissimo.com/")
    )
    ->addVirtualHost(
        (new VirtualHost("*", "443", "www.sito-bellissimo.com ssl REDIRECT"))
            ->addDirective("ServerName", "www.sito-bellissimo.com")
            ->addDirective("DocumentRoot", "/var/www/sito-bellissimo.com/public_html")
            ->addBreak()
            ->addDirectory(
                (new Directory("/var/www/sitobellissimo.com/public_html"))
                    ->addDirective("Options", "FollowSymLinks")
                    ->addDirective("AllowOverride", "All")
                    ->addDirective("Require", "all granted")
                    ->addBreak()
                    ->redirectIf404("301", "sito-bellissimo.com", "https://www.sitobellissimo.com/")
            )
            ->addBreak()
            ->enableSSL(
                "/path/to/sitobellissimo_com.crt",
                "/path/to/sitobellissimo_com.key",
                "/path/to/sitobellissimo_com.ca-bundle"
            )
            ->addBreak()
            ->addDirective("ErrorLog", "/var/www/sitobellissimo.com/logs/error.log")
            ->addDirective("LogLevel", "warn")
            ->addDirective("CustomLog", "/var/www/sitobellissimo.com/logs/access.log combined")
    );

// classic method

$apache_config_2 = new ApacheConf("Start comment", "Finish comment");

$site_1 = new VirtualHost("*", "443", "sitobellissimo.com ssl");
$site_1->addDirective("ServerName", "sitobellissimo.com");
$site_1->addDirective("DocumentRoot", "/var/www/sitobellissimo.com/public_html");
$site_1->addBreak();

$directory_1 = new Directory("/var/www/sitobellissimo.com/public_html");
$directory_1->addDirective("Options", "FollowSymLinks");
$directory_1->addDirective("AllowOverride", "All");
$directory_1->addDirective("Require", "all granted");
$site_1->addDirectory($directory_1);
$site_1->addBreak();
$site_1->enableSSL("/path/to/sitobellissimo_com.crt", "/path/to/sitobellissimo_com.key", "/path/to/sitobellissimo_com.ca-bundle");
$site_1->addBreak();
$site_1->addDirective("ErrorLog", "/var/www/sitobellissimo.com/logs/error.log");
$site_1->addDirective("LogLevel", "warn");
$site_1->addDirective("CustomLog", "/var/www/sitobellissimo.com/logs/access.log combined");


$apache_config_2->addVirtualHost($site_1);

echo "<b>Short nested method</b>";
echo "<pre>";
echo htmlentities(print_r($apache_config->toString(), true));
echo "</pre>";
echo "<b>Classic method</b>";
echo "<pre>";
echo htmlentities(print_r($apache_config_2->toString(), true));
echo "</pre>";
