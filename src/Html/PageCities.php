<?php
 /**
 * @author Nagy Gergely, Király Gábor 
 **/
namespace App\Html;

use App\Html\AbstractPage;

class PageCities extends AbstractPage
{
    static function table(array $cities)
    {
        echo '<h1>Városok</h1>';
        self::generateAlphabetButtons($cities);
        echo '<table id="cities-table">';
        self::tableHead();
        echo '
        <style>
        body {font-family: Arial, Helvetica, sans-serif;}

        .modal {
          display: none; 
          position: fixed; 
          z-index: 1; 
          padding-top: 100px; 
          left: 0;
          top: 0;
          width: 100%; 
          height: 100%; 
          overflow: auto; 
          background-color: rgb(0,0,0);
          background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
          background-color: #fefefe;
          margin: auto;
          padding: 20px;
          border: 1px solid #888;
          width: 80%;
        }

        .close {
          color: #aaaaaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
        }

        .close:hover,
        .close:focus {
          color: #000;
          text-decoration: none;
          cursor: pointer;
        }
        </style>
        <div id="myModal" class="modal">

        <div class="modal-content">
        <span class="close">&times;</span>
        <p>';self::editor();echo'</p>
        </div>

        </div>';
        self::tableBody($cities);
        echo '</table>';
    }

    static function tableHead()
    {
        echo '
        <thead>
            <tr>
                <th class="id-col">ID</th>
                <th>Város neve</th>
                <th>Irányítószám</th>
                <th style="float: right; display: flex">
                    Művelet&nbsp;
                    <button id="myBtn">+</button>';
        echo'
                </th>
            </tr>
        </thead>';
    }

    static function tableBody(array $cities)
    {
        echo '<tbody>';
        foreach ($cities as $city) {
            echo "
            <tr>
                <td>{$city['id']}</td>
                <td>{$city['city']}</td>
                <td>{$city['zip_code']}</td>
                <td class='flex'>
            <form method='post' action='' class='inline-form'>
            <input type='hidden' name='id_county' value='{$city['id_county']}' />
                                <button type='submit'
                                    name='btn-edit-city'
                                    value='{$city['id']}'
                                    title='Szerkesztés'>
                                    <i class='fa fa-edit'></i>
                                </button>
                            </form>
                <form method='post' action=''>
                    <button type='submit' id='btn-del-city-{$city['id']}' name='btn-del-city' value='{$city['id']}' title='Töröl'>
                        <i class='fa fa-trash'></i>
                    </button>
                </form>
            </td>
            </tr>";
        }
        echo '</tbody>';
        echo '<script>
            var modal = document.getElementById("myModal");
 
            var btn = document.getElementById("myBtn");
 
            var span = document.getElementsByClassName("close")[0];
 
            btn.onclick = function() {
              modal.style.display = "block";
            }
 
            span.onclick = function() {
              modal.style.display = "none";
            }
 
            window.onclick = function(event) {
              if (event.target == modal) {
                modal.style.display = "none";
              }
            }
        </script>';
    }


    static function dropdown(array $entities, $selectedId = 0){
        echo '<h1>Városok</h1>';
        echo '<form method="post" action="">'; 
        echo '<select name="id_county" required>'; 
        foreach ($entities as $entity) {
            $selected = "";
            if($entity['id'] == $selectedId){
                $selected = "selected";
            }
            echo "<option value='{$entity['id']}' $selected >{$entity['name']}</option>";
        }
        echo '</select>';
        echo '<button type="submit" name="btn-cities">Submit</button>'; 
        echo '</form>';
    }

    static function editForm(array $city) {
        echo "
        <h2>Város szerkesztése</h2>
        <form method='post' action=''>
            <input type='hidden' name='id' value='{$city['id']}' />
            <input type='text' name='city' value='{$city['city']}' required  />
            <button type='submit' name='btn-update-city'>Mentés</button>
            <button type='submit' name='btn-cancel'>Mégse</button>
        </form>";
    }

    static function editor() {
        echo '
        <form name="city-editor" method="post" action="">
        <input type="hidden" id="id" name="id">
        <input type="search" id="city" name="city" placeholder="Város" required>
        <input type="search" id="zip_code" name="zip_code" placeholder="Irányítószám" required>
        <button type="submit" id="btn-save-city" name="btn-save-city" title="Ment"><i class ="fa fa-save"></i></button>
        <button type="button" id="btn-cancel-city" title="Mégse"><i class="fa fa-times"></i></button>
    </form>';
    }

    static function generateAlphabetButtons(array $cities)
    {
       
        $letters = range('A', 'Z');  
        $availableLetters = array_map(function($city) {
            return strtoupper(substr($city['city'], 0, 1));
        }, $cities);
 
        $letters = array_unique($availableLetters);
        sort($letters);  
 
        echo "<div class='alphabet-buttons'>";
        foreach ($letters as $letter) {
            echo "<form method='post' action='' style='display:inline'>
            <button type='submit' name='btn-alphabet' value='$letter'>$letter</button>
            </form>";
        }
        echo "</div>";
    }

}



