<?php


class Recipes {

    public function __construct($whitelist=null) {
        if($whitelist) $this->whitelist = $whitelist;
    }

    public function search($phrase) {
        if (!$phrase) return;
        $q = "SELECT id FROM recipe WHERE rcp_headline LIKE '%".$phrase."%'";
        #if ($this->whitelist) { $q .= " AND id in }
        return $this->fetch($q);
    }

    public function all() {
        $q = "SELECT id FROM recipe";
        return $this->fetch($q);
    }

    private function fetch($q) {
        if (!$q) return;
        global $db;
        $items = array();
        $a = $db->fetch_all_array($q);
        foreach ($a as $row) {
            $id = (int)$row['id'];
            if ($id) $items[] = new Recipe($id);
        }
        return $items;
    }

}


class Recipe {

    public function __construct($id=0) {
        $this->id = (int)$id;
        $this->headline = "";
        $this->body  = "";
        $this->notes  = "";
        $this->fetch();
    }

    public function save() {
        if ((!$this->headline) and (!$this->body) and (!$this->notes)) return;
        global $db;
        if ($this->id) {
            $q = "UPDATE recipe SET";
            $q .= " rcp_headline='".$this->headline."',";
            $q .= " rcp_body='".$this->body."',";
            $q .= " rcp_notes='".$this->notes."'";
            $q .= " WHERE id=" . $this->id;
            $db->query($q);
        } else {
            $q = "INSERT INTO recipe";
            $q .= " (rcp_headline, rcp_body, rcp_notes)";
            $q .= " VALUES ";
            $q .= "('".$this->headline."','". $this->body."','".$this->notes."')";
            $db->query($q);
            $this->id = $db->last_id();
        }
        $this->fetch();
    }

    public function remove() {
        if (!$this->id) return;
        global $db;
        $q = "DELETE recipe where id=". $this->id;
        $db->query($q);
        $this->id = 0;
    }

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
            $this->image_id = $a[0]['rcp_img_id'];
            $this->created = $a[0]['created'];
        } else {
            $this->id = 0;
        }
    }

    public function getIngredients() {
        if (!$this->id) return;
        global $db;
        $this->ingredients = array();
        $q = "SELECT * FROM recipe_ingredient WHERE rcp_id=". $this->id;
        $a = $db->fetch_all_array($q);
        foreach ($a as $i) {
            $this->ingredients[] = new Ingredient($i['tag_id']);
        }
        return $this->ingredients;
    }

    public function addIngredient($item) {
        if (!$item) return;
        global $db;
        $ingr = new Ingredient($item);
        return new RecipeIngredient($this->id, $ingr->id);
    }

    public function getTags() {
        if (!$this->id) return;
        global $db;
        $this->tags = array();
        $q = "SELECT * FROM recipe_tag WHERE rcp_id=". $this->id;
        $a = $db->fetch_all_array($q);
        foreach ($a as $i) {
            $this->tags[] = new Tag($i['tag_id']);
        }
        return $this->tags;
    }

    public function addTag($item) {
        if (!$item) return;
        global $db;
        $tag = new Tag($item);
        return new RecipeTag($this->id, $tag->id);
    }

}


class Ingredient {

    public function __construct($id=0) {
        $this->id = (int)$id;
        $this->item = ""; 
        $this->fetch();
    }

    public function save() {
        if ((!$this->item)) return;
        global $db;
        if ($this->id) {
            $q = "UPDATE ingredient SET";
            $q .= " ingr_item='".$this->item."',";
            $q .= " WHERE id=" . $this->id;
            $db->query($q);
        } else {
            $item = strtolower($this->item);
            $q = "SELECT * FROM ingredient WHERE ingr_item='". $item ."'";
            $a = $db->fetch_all_array($q);
            $this->id = ($a) ? $a[0]['id'] : null;
            if(!$this->id) {
                $q = "INSERT INTO ingredient (ingr_item) VALUES ('". $item ."')";
                $db->query($q);
                $this->id = $db->last_id();
            }
        }
        $this->fetch();
    }

    public function remove() {
        if (!$this->id) return;
        global $db;
        $q = "DELETE ingredient where id=". $this->id;
        $db->query($q);
        $this->id = 0;
    }

    private function fetch() {
        if (!$this->id) return;
        global $db;
        $q = "SELECT * from ingredient where id =". $this->id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->id = $a[0]['id'];
            $this->item = $a[0]['ingr_item'];
            $this->image_id = $a[0]['ingr_img_id'];
            $this->created = $a[0]['created'];
        } else {
            $this->id = 0; 
        }
    }

}

class RecipeIngredient {

    public function __construct($rcp_id=0, $ingr_id=0) {
        $this->rcp_id = (int)$rcp_id;
        $this->ingr_id = (int)$ingr_id;
        $this->amount = ""; 
        $this->order = 0; 
        $this->fetch();
    }

    public function save() {
        if ((!$this->amount) and (!$this->order)) return;
        global $db;
        if (($this->rcp_id) and ($this->ingr_id)) {
            $q = "UPDATE recipe_ingredient SET";
            $q .= " ingr_amount='".$this->amount."',";
            $q .= " ingr_order='".$this->order."',";
            $q .= " WHERE rpc_id=" . $this->rcp_id;
            $q .- " AND ingr_id=" . $this->ingr_id;
            $db->query($q);
        } else {
            $q = "INSERT INTO recipe_ingredient (rcp_id,ingr_id,ingr_amount,ingr_order) ";
            $q .= "VALUES (".$this->rcp_id.",".$this->ingr_id.",".$this->amount.",".$this->order.")";
            $db->query($q);
        }
        $this->fetch();
    }

    public function remove() {
        if ((!$this->rcp_id) and (!$this->ingr_id)) return;
        global $db;
        $q = "DELETE recipe_ingredient WHERE rcp_id=". $this->rcp_id;
        $q .= " AND ingr_id=". $this->ingr_id;
        $db->query($q);
        $this->rcp_id = 0;
        $this->ingr_id = 0;
    }

    private function fetch() {
        if ((!$this->rcp_id) and (!$this->ingr_id)) return;
        global $db;
        $q = "SELECT * FROM recipe_ingredient  WHERE rcp_id=". $this->rcp_id; 
        $q .= " AND ingr_id=". $this->ingredient_id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->rcp_id = $a[0]['rcp_id'];
            $this->ingr_id = $a[0]['ingr_id'];
            $this->amount = $a[0]['ingr_amount'];
            $this->order = $a[0]['ingr_order'];
            $this->image_id = $a[0]['ingr_img_id'];
            $this->created = $a[0]['created'];
        } else {
            $this->rcp_id = 0; 
            $this->ingr_id = 0; 
        }
    }

}


class Tag {

    public function __construct($id=0) {
        $this->id = (int)$id;
        $this->item = ""; 
        $this->fetch();
    }

    public function save() {
        if ((!$this->item)) return;
        global $db;
        if ($this->id) {
            $q = "UPDATE tag SET";
            $q .= " ingr_item='".$this->item."',";
            $q .= " WHERE id=" . $this->id;
            $db->query($q);
        } else {
            $item = strtolower($this->item);
            $q = "SELECT * FROM tag WHERE tag_item='". $item ."'";
            $a = $db->fetch_all_array($q);
            $this->id = ($a) ? $a[0]['id'] : null;
            if(!$this->id) {
                $q = "INSERT INTO tag (tag_item) VALUES ('". $item ."')";
                $db->query($q);
                $this->id = $db->last_id();
            }
        }
        $this->fetch();
    }

    public function remove() {
        if (!$this->id) return;
        global $db;
        $q = "DELETE tag where id=". $this->id;
        $db->query($q);
        $this->id = 0;
    }

    private function fetch() {
        if (!$this->id) return;
        global $db;
        $q = "SELECT * from tag where id =". $this->id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->id = $a[0]['id'];
            $this->item = $a[0]['tag_item'];
            $this->image_id = $a[0]['tag_img_id'];
            $this->created = $a[0]['created'];
        } else {
            $this->id = 0; 
        }
    }

}

class RecipeTag {

    public function __construct($rcp_id=0, $tag_id=0) {
        $this->rcp_id = (int)$rcp_id;
        $this->tag_id = (int)$tag_id;
        $this->rank = 0;
        $this->fetch();
    }

    public function save() {
        if (!$this->rank) return;
        global $db;
        if (($this->rcp_id) and ($this->ingr_id)) {
            $q = "UPDATE recipe_tag SET";
            $q .= " tag_rank='".$this->rank."',";
            $q .= " WHERE rpc_id=" . $this->rcp_id;
            $q .- " AND tag_id=" . $this->tag_id;
            $db->query($q);
        } else {
            $q = "INSERT INTO recipe_tag (rcp_id,tag_id,tag_rank) ";
            $q .= "VALUES (".$this->rcp_id.",".$this->tag_id.",".$this->rank.")";
            $db->query($q);
        }
        $this->fetch();
    }

    public function remove() {
        if ((!$this->rcp_id) and (!$this->tag_id)) return;
        global $db;
        $q = "DELETE recipe_tag WHERE rcp_id=". $this->rcp_id;
        $q .= " AND tag_id=". $this->tag_id;
        $db->query($q);
        $this->rcp_id = 0;
        $this->ingr_id = 0;
    }

    private function fetch() {
        if ((!$this->rcp_id) and (!$this->tag_id)) return;
        global $db;
        $q = "SELECT * FROM recipe_ingredient  WHERE rcp_id=". $this->rcp_id; 
        $q .= " AND ingr_id=". $this->ingredient_id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->rcp_id = $a[0]['rcp_id'];
            $this->ingr_id = $a[0]['tag_id'];
            $this->order = $a[0]['tag_rank'];
            $this->image_id = $a[0]['tag_img_id'];
            $this->created = $a[0]['created'];
        } else {
            $this->rcp_id = 0; 
            $this->tag_id = 0; 
        }
    }

}

class Image {

    public function __construct($id=0) {
        $this->id = (int)$id;
        $this->fetch();
    }

    public function remove() {
        if (!$this->id) return;
        global $db;
        $q = "DELETE image where id=". $this->id;
        $db->query($q);
        $this->id = 0;
    }

    private function fetch() {
        if (!$this->id) return;
        global $db;
        $q = "SELECT * from image where id =". $this->id;
        $a = $db->fetch_all_array($q);
        if ($a) {
            $this->id = $a[0]['id'];
            $this->filepath = $a[0]['tag_filepath'];
            $this->created = $a[0]['created'];
        } else {
            $this->id = 0; 
        }
    }

}

?>

