<?php
namespace App\Html;
 
use App\Html\AbstractPage;  
 
class PageCounties extends AbstractPage
{
    static function table(array $entities){
        echo '<h1>Megyék</h1>';
        self::searchBar();
        echo'<table id="counties-table">';
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
        self::tableBody($entities);
        echo "</table>";
    }
 
    static function tableHead()
    {
        echo '
        <thead>
            <tr>
                <th class="id-col">#</th>
                <th>Megnevezés</th>
                <th style="float: right; display: flex">
                    Művelet&nbsp;
                    <button id="myBtn">+</button>';
        echo'
                </th>
            </tr>
            <tr id="editor" class="hidden">';
            echo '
            </tr>
        </thead>';
    }
 
    static function editor() {
        echo '
        <form name="county-editor" method="post" action="">
        <input type="hidden" id="id" name="id">
        <input type="search" id="name" name="name" placeholder="Megye" required>
        <button type="submit" id="btn-save-county" name="btn-save-county" title="Ment"><i class ="fa fa-save"></i></button>
        <button type="button" id="btn-cancel-county" title="Mégse"><i class="fa fa-times"></i></button>
    </form>';
    }

    static function editForm($county) {
        echo "
        <h2>Megye szerkesztése</h2>
        <form method='post' action=''>
            <input type='hidden' name='id' value='{$county['id']}'>
            <input type='text' name='name' value='{$county['name']}' required>
            <button type='submit' name='btn-update-county'>Mentés</button>
            <button type='submit' name='btn-cancel'>Mégse</button>
        </form>";
    }
 
    static function tableBody(array $entities) {
        
        echo '<tbody>';
        $i = 0;
        foreach ($entities as $entity) {
        echo "
        <tr class='" . (++$i % 2 ? "odd" : "even") . "'>
            <td>{$entity['id']}</td>
            <td>{$entity['name']}</td>
            <td class='flex'>
            <form method='post' action='' class='inline-form'>
                                <button type='submit'
                                    name='btn-edit-county'
                                    value='{$entity['id']}'
                                    title='Szerkesztés'>
                                    <i class='fa fa-edit'></i>
                                </button>
                            </form>
                <form method='post' action=''>
                    <button type='submit' id='btn-del-county-{$entity['id']}' name='btn-del-county' value='{$entity['id']}' title='Töröl'>
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


}
?>