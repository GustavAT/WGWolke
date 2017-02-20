<?php
require_once("Entity.php");
require_once("../code/Resources.php");

class ToDoList extends Entity {
    private $community_oid;
    private $list_name;
    private $creator_oid;

    public function __construct($_object_id, $_date_created, $_community_oid,
            $_list_name, $_creator_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->community_oid = $_community_oid;
        $this->list_name = $_list_name;
        $this->creator_oid = $_creator_oid;        
    }

    public static function fromRecord($record) {
        return new ToDoList(
            $record["oid"],
            $record["date_created"],
            $record["community_oid"],
            $record["list_name"],
            $record["creator_oid"]
        );
    }

    // getter
    public function getCommunityOid() {return $this->community_oid;}
    public function getListName() { return $this->list_name; }
    public function getCreatorOid() {return $this->creator_oid;}    

    // setter
    public function setCommunityOid($_value) {$this->community_oid = $_value;}
    public function setListName($_value) {$this->list_name = $_value;}
    public function setCreatorOid($_value) {$this->creator_oid = $_value;}    

    public function toString() {
        return $this->list_name;
    }

    public function createView($user_oid) { ?>
        <div class="panel panel-<?php echo $this->creator_oid == $user_oid ? "yellow" : "primary"; ?>">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="<?php echo "icon-todo "; ?> scaling-normal">
                        </div>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> <?php echo htmlspecialchars($this->list_name); ?> </div>                        
                    </div>
                </div>                
            </div>    
            <a href="./ToDoListDetails.php?list=<?php echo $this->object_id; ?>">
                <div class="panel-footer">
                    <span class="pull-left"><?php echo Resources::$text_details; ?></span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    <?php }
}