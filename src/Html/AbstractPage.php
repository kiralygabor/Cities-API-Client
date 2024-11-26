<?php
 
namespace App\Html;
 
use App\Interfaces\PageInterface;
 
abstract class AbstractPage implements PageInterface
{
    static function head()
    {
        echo '<!DOCTYPE html>
        <html lang="hu-hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="css/style.css">
            <title>REST API Ügyfél</title>
            
            <!-- Script -->
            <script src="js/jquery-3.7.1.js" type="text/javascript"></script>
        </head>';
    }

    static function nav()
    {
        echo '
        <nav>
            <form name = "nav" method = "post" action = "index.php">
                <button type = "submit" name = "btn-home">
                    <i class = "fa fa-home" title = "Kezdőlap"></i>
                </button>
                <button type = "submit" name = "btn-counties">Megyék</button>
                <button type = "submit" name = "btn-cities">Városok</button>
            </form>
        </nav>';
    }

    static function footer()
    {
        echo '
        <footer>
        Copyright Nagy Gergely
        </footer>
        </html>';
    }

    abstract static function tableHead();

    abstract static function tableBody(array $entities);

    abstract static function table(array $entities);

    abstract static function editor();

    static function searchbar()
    {
        echo '
        <form action="search.php" method="get">
    <input type="text" name="keyword" />
    <input type="submit" value="Search" />
        </form>
        <br>';
    }

   


}