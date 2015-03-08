<?php


class Recipes {
    public function __construct($whitelist=null) {
        if($whitelist) $this->whitelist = $whitelist;
    }

    public function search($phrase) {
        $q = "SELECT id FROM recipe WHERE rcp_headline LIKE '%".$phrase."%'";
        #if ($this->whitelist) { $q .= " AND id in }
        return $this->getRecipes($q);
    }

    public function all() {
        $q = "SELECT id FROM recipe";
        return $this->getRecipes($q);
    }

    private function getRecipes($q) {
        global $db;
        $recipes = array();
        $a = $db->fetch_all_array($q);
        foreach ($a as $i) {
            $id = (int)$i['id'];
            if ($id) $recipes[] = new Recipe($id);
        }
        return $recipes;
    }

}


class Recipe {

    public function __construct($id=0) {
        $this->id = (int)$id;
        $this->headline = "";
        $this->body = "";
        $this->fetch();
    }

    public function update() {
        if (!$this->id) return;
        return array();
        global $db;
        $q = "UPDATE recipe SET";
        $q .= " rcp_headline='".$this->headline."',";
        $q .= " rcp_body='".$this->body."',";
        $q .= " rcp_notes='".$this->notes."'";
        $q .= " WHERE id=" . $this->id;
        $db->query($q);
        $this->fetch();
    }

    public function create() {
        global $db;
        $q = "INSERT INTO recipe";
        $q .= " (rcp_headline, rcp_body, rcp_notes)";
        $q .= " VALUES ";
        $q .= "('".$this->headline."','". $this->body."','".$this->notes."')";
        $db->query($q);
        $this->id = $db->last_id();
        $this->fetch();
    }

    public function addIngredient($ingr) {
        if (!$ingr) return;
        global $db;
        $q = "SELECT * FROM ingredient WHERE ingr_name='". $ingr ."'";
        $a = $db->fetch_all_array($q);
        $ingr_id = ($a) ? $a[0]['id'] : null;
        if(!$ingr_id) {
            $q = "INSERT INTO ingredient (ingr_name) VALUES ('".$ingr ."')";
            $db->query($q);
            $ingr_id = $db->last_id();
        }
        $q = "SELECT * FROM recipe_ingredient WHERE ingr_id=". $ingr_id ." AND rcp_id=".$this->id;
        $a = $db->fetch_all_array($q);
        $rcp_id = ($a) ? $a[0]['rcp_id'] : null;
        if (!$rcp_id) {
            $q = "INSERT INTO recipe_ingredient (rcp_id,ingr_id) VALUES (".$this->id.",".$ingr_id.")";
            $db->query($q);
        }
    }

    public function getIngredients() {
        if (!$this->id) return;
        global $db;
        $q = "SELECT * FROM recipe_ingredient WHERE rcp_id=". $this->id;
        $a = $db->fetch_all_array($q);
        $ingredients = array();
        foreach ($a as $i) {
           $ingredients[] = new Ingredient($i['ingr_id']); 
        }
        $this->ingredients = $ingredients;
        return $ingredients;
    }

    public function getTags() { return; }

    private function fetch() {
        if (!$this->id) return;
        global $db;
        $q = "SELECT * from recipe where id =". $this->id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->id = $a[0]['id'];
            $this->headline = $a[0]['rcp_headline'];
            $this->body = $a[0]['rcp_body'];
            $this->notes = $a[0]['rcp_notes'];
            $this->created = $a[0]['created'];
        }
    }

}

class Ingredient {
    public function __construct($id=0) {
        $this->id = (int)$id;
        $this->ingredient = ""; 
        $this->desc = ""; 
        $this->fetch();
    }

    private function fetch() {
        if (!$this->id) return;
        global $db;
        $q = "SELECT * FROM ingredient LEFT JOIN recipe_ingredient ON ingredient.id = recipe_ingredient.ingr_id WHERE id =". $this->id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->id = $a[0]['id'];
            $this->ingredient = $a[0]['ingr_name'];
            $this->desc = $a[0]['ingr_desc'];
            $this->order = $a[0]['ingr_order'];
            $this->notes = $a[0]['ingr_notes'];
            $this->created = $a[0]['created'];
        }
    }
}
?>

