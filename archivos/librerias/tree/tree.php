<?php
class Tree
{
	private $_dbh;
	private $_elements = array();

	public function __construct()
	{
		try{
			$this->_dbh = new PDO("mysql:host=localhost;dbname=arbol", "root", "");
			$this->_dbh->exec("SET CHARACTER SET utf8");
	        $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $this->_dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	    } 
	    catch (PDOException $e) 
	    {
            print "Error!: " . $e->getMessage();
            die();
        }
	}

	public function get()
	{
		$query = $this->_dbh->prepare("SELECT * FROM elementos");
		$query->execute();
		$this->_elements["masters"] = $this->_elements["childrens"] = array();

		if($query->rowCount() > 0)
		{
			foreach($query->fetchAll() as $element)
			{
				if($element["parent_id"] == 0)
				{
					array_push($this->_elements["masters"], $element);
				}
				else
				{
					array_push($this->_elements["childrens"], $element);
				}
			}
		}
		return $this->_elements;
	}

	public static function nested($rows = array(), $parent_id = 0)
	{
		$html = "";
		if(!empty($rows))
		{
			$html.="<ul>";
			foreach($rows as $row)
			{
				if($row["parent_id"] == $parent_id)
				{
					$html.="<li style='margin:5px 0px'>";
					$html.="<span><i class='glyphicon glyphicon-folder-open'></i></span>";
					$html.="<a href='#' data-status='{$row["have_childrens"]}' style='margin: 5px 6px' class='btn btn-warning btn-xs btn-folder'>";
					if($row["have_childrens"] == 1)
					{
						$html.="<span class='glyphicon glyphicon-minus-sign'></span>".$row['label']."</a>";
					}
					else
					{
						$html.="<span class='glyphicon glyphicon-plus-sign'></span>".$row['label']."</a>";
					}
					$html.=self::nested($rows, $row["id"]);
					$html.="</li>";
				}
			}
			$html.="</ul>";
		}
		return $html;
	}
}