<?php


class Recipe {

    public function __construct($id=0) {
        $this->id = (int)$id;
        $this->headline = "";
        $this->body = "";
        $this->notes = "";
        $this->tags = array();
        $this->ingredients = array();
        $this->fetch();
    }

    public function update() {
        if (!$this->id) return;
        return array();
        global $db;
        $q = "UPDATE recipe SET";
        $q .= " recipe_headline='".$this->headline."',";
        $q .= " recipe_body='".$this->body."',";
        $q .= " recipe_notes='".$this->notes."'";
        $q .= " WHERE id=" . $this->id;
        $db->query($q);
        $this->fetch();
    }

    public function create() {
        global $db;
        $q = "INSERT INTO recipe";
        $q .= " (recipe_headline, recipe_body, recipe_notes)";
        $q .= " VALUES ";
        $q .= "('".$this->headline."','". $this->body."','".$this->notes."')";
        $db->query($q);
        $this->id = $db->last_id();
        $this->fetch();
    }

    private function fetch() {
        if (!$this->id) return;
        global $db;
        $q = "SELECT * from recipe where id =". $this->id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->id = $a[0]['id'];
            $this->headline = $a[0]['recipe_headline'];
            $this->body = $a[0]['recipe_body'];
            $this->notes = $a[0]['recipe_notes'];
        }
        #todo: get tags
        #todo: get ingredients 
    }

}

class Recipes {
    public function __construct($whitelist=null) {
        # do something with the withelist
    }

    public function search($phrase) {
        return array();
    }

    public function all() {
        global $db;
        $q = "SELECT id FROM recipe";
        $a = $db->fetch_all_array($q);
        $recipes = array();
        foreach ($a as $i) {
            $id = (int)$i['id'];
            if ($id) $recipes[] = new Recipe($id);
        }
        return $recipes;
    }

}

?>

