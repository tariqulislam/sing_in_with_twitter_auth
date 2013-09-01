<?php

class Category {

    //The connection to a MySQL server
    var $dbConnection = null;
    //The categories as an array
    var $categories = array();
    var $inputType = 'checkbox';
    var $checked = array();

    function __construct($cnn) {
        $this->Category($cnn);
    }

    function Category($cnn) {
        //Initialize the connection and the categories
        $this->dbConnection = $cnn;
        $this->categories = array();
        $this->getCategories();
    }

    function getCategories() {
        //Read the records and fill the categories
        $query = "SELECT * FROM categories ORDER BY category_id";
        $result = mysqli_query($this->dbConnection,$query) or die(mysqli_error($this->dbConnection));

        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $this->categories[$i] = $row;
            $i++;
        }
        //Free the resource
        mysqli_free_result($result);
    }

    function viewTree() {
        //Generate tree list
        $output = '';
        for ($i = 0; $i < count($this->categories); $i++) {
            if ($this->categories[$i]["category_parent_id"] == "0") {
                $output .= "<li title=\"" . $this->categories[$i]["category_description"] . "\">";
                $output .= "<a></a>";
                
                if (in_array($this->categories[$i]["category_id"], $this->checked)) {
                    $output .='<input checked="checked" type="' . $this->inputType . '" name="categories[]" value="' . $this->categories[$i]["category_id"] . '"/>';
                } else {
                    $output .='<input type="' . $this->inputType . '" name="categories[]" value="' . $this->categories[$i]["category_id"] . '"/>';
                }

                $output.= $this->categories[$i]["category_name"] . "\n<ul>\n";
                $output .= $this->getAllChildren($this->categories[$i]["category_id"]);
                $output .= "\n</ul>\n</li>";
            }
        }
        return $output;
    }

    function getAllChildren($parent_id, $inputType = 'checkbox') {
        //Get all the nodes for particular ID
        $output = "";
        for ($i = 0; $i < count($this->categories); $i++) {
            if ($this->categories[$i]["category_parent_id"] == $parent_id) {
                $output .= "<li title=\"" . $this->categories[$i]["category_description"] . "\">";
                $output .= "<a></a>";
                if (in_array($this->categories[$i]["category_id"], $this->checked)) {
                    $output .='<input checked="checked"  type="' . $this->inputType . '" name="categories[]" value="' . $this->categories[$i]["category_id"] . '"/>';
                } else {
                    $output .='<input type="' . $this->inputType . '" name="categories[]" value="' . $this->categories[$i]["category_id"] . '"/>';
                }

                $output .=$this->categories[$i]["category_name"] . "\n<ul>\n";
                $output .= $this->getAllChildren($this->categories[$i]["category_id"]);
                $output .= "\n</ul>\n</li>";
            }
        }
        return $output;
    }

}

?>