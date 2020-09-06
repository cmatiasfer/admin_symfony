<?php
    namespace App\Service;

    class SearchField
    {
        // Generate Input[type="text"] Keyword
        function keywordField($id,$label,$columns="col-12",$value="") {
            if ($value == "all") { $value = ""; }
            $inputText = "<div class='form-group no-gutter-custom ". $columns ."'><label for='".$id."' class='required'>".$label."</label><input type='text' id='".$id."' name='".$id."' class='form-control' value='".$value."'></div>";
            return $inputText;
        }

        // Generate Select - Automatic Key/Value Pairs
        function select($id,$label,$columns="col-12",$options,$active="") {
            $select = "<div class='form-group no-gutter-custom " . $columns ."'>";
            $select .= "<label>".$label."</label>";
            $select .= "<select name='".$id."' id='".$id."' class='form-control'>";
            $select .= "<option value='all'>All</option>";
            $count=1;

            foreach($options as $key =>$option){
                $select .= "<option value='".$count."'" . (($count == $active) ? 'selected': '') .">" . $option . "</option>";
                $count++;
            }

            $select .= "</select>";
            $select .="</div>";

            return $select;
        }

        // Generate Select - Automatic CustomKey/Value Pairs
        function selectField($id,$label,$columns="col-12",$options,$active="") {
            $select  = "<div class='form-group no-gutter-custom " . $columns ."'>";
            $select .= "<label>".$label."</label>";
            $select .= "<select name='".$id."' id='".$id."' class='form-control'>";
            $select .= "<option value='all'>All</option>";

            foreach($options as $key => $value) {
                $select .= "<option value='".$value."'" . (($value == $active) ? 'selected' : '') . ">" . $key . "</option>";
            }

            $select .= "</select>";
            $select .="</div>";

            return $select;
        }

        // Generate Select for Entites- Automatic object->getId/object->getName
        function selectEntity($id,$label,$columns="col-12",$options,$active="") {
            $select  = "<div class='form-group no-gutter-custom " . $columns ."'>";
            $select .= "<label>".$label."</label>";
            $select .= "<select name='".$id."' id='".$id."' class='form-control'>";
            $select .= "<option value='all'>All</option>";

            foreach($options as $option) {
                $select .= "<option value='" . $option->getId() . "'" . (($option->getId() == $active) ? 'selected': '') .">" . $option->getName() . "</option>";
            }

            $select .= "</select>";
            $select .="</div>";

            return $select;

        }
    }
?>