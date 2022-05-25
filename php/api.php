<?php
	class Entity{
		public static $bdd;
		public static $count = 0;
		public $table, $pageSize, $url, $result;

		public function __construct(){}

		private function writeLine(array $d){
			$ct = '';
			foreach($d as $val)
				$ct=$ct.'<td>'.$val.'</td>';
			echo '<tr>'.$ct.'</tr>';
		}

		public function max($KEY, $filter=NULL){
			$query = 'SELECT max('.$KEY.') as cn FROM '.$this->table .($filter==NULL? '': 'WHERE '.$filter);
			$r = Entity::$bdd->query($query);
			$d = $r->fetch();
			return $d['cn'];
		}

		public function getTable(array $d=NULL, $filter=NULL, $limit=NULL){
			$query = $d==NULL ? 'SELECT * FROM '.$this->table .($filter==NULL? '': 'WHERE '.$filter).($limit==NULL? '': ' LIMIT 0,'.$limit)
			: 'SELECT '.implode(',', $d).' FROM '.$this->table.($filter==NULL? '': 'WHERE '.$filter).($limit==NULL? '': ' LIMIT 0,'.$limit);

			$r = Entity::$bdd->query($query);
			echo '<h4>'.$query.'</h4>';

			echo '<table border="1">';
				while($data=$r->fetch(PDO::FETCH_ASSOC))
					$this->writeLine($data);

			echo '</table>';
		}

		/*public function getPage(array $d=NULL, $filter=NULL, $limit=NULL){
			$query = $d==NULL ? 'SELECT * FROM '.$this->table .($filter==NULL? '': 'WHERE '.$filter).($limit==NULL? '': ' LIMIT 0,'.$limit)
			: 'SELECT '.implode($d, ',').' FROM '.$this->table.($filter==NULL? '': 'WHERE '.$filter).($limit==NULL? '': ' LIMIT 0,'.$limit);

			$r = Entity::Entity::$bdd->query($query);
			echo '<h4>'.$query.'</h4>';

			echo '<table border="1">';
				while($data=$r->fetch(PDO::FETCH_ASSOC))
					$this->writeLine($data);

			echo '</table>';
		}*/

		public function getPage($page=0, $where=NULL){
			$min = $page * $this->pageSize;
			$max = $this->pageSize;
			$q = 'SELECT * FROM '.$this->table.($where==NULL ? ' ORDER BY id DESC LIMIT '.$max.' OFFSET '.$min
										 :	' WHERE '.$where.' ORDER BY id DESC LIMIT '.$max.' OFFSET '.$min);

			//echo $q;
		  $r = Entity::$bdd->query($q);
		  $ar = array();
		  $class = ucfirst($this->table);
		  while($d=$r->fetch()){
  			$var = new $class();
  			$var->hydrate($d);
  			array_push($ar, $var);
		  }
			Entity::$count = count($ar);
		  return $ar;
		}

		public function rawPage($query, $order, $page=0, $ord='DESC'){
			$min = $page * $this->pageSize;
			$max = $this->pageSize;

			//echo '<h4>'.$query.' ORDER BY '.$order.' DESC LIMIT '.$max.' OFFSET '.$min.'</h4>';
			$r = Entity::$bdd->query($query.' ORDER BY '.$order.' '.$ord.' LIMIT '.$max.' OFFSET '.$min);
			$ar = array();

			while($d = $r->fetch(PDO::FETCH_ASSOC))
				array_push($ar, $d);

			return $ar;
		}

		/*public function rawBtn($query, $class){
		  $r=Entity::$bdd->query($query);
			$d = $r->rowCount();

				echo '
				<script type="text/javascript">
					$(\'.'.$class.'\').pagination({
							items: '.$d.',
							itemsOnPage: '.$this->pageSize.',
							cssStyle: \'light-theme\',
							onPageClick: function(pageNumber, event){
									init(pageNumber-1);
							}
					});
				</script>';
		 }*/
		 public function psize($query, $id='SIZE'){
			 	$r=Entity::$bdd->query($query);
 				$d = $r->rowCount();
				echo '<input type="hidden" id="'.$id.'" value="'.$d.'" />';
		 }

		public function psize2($query){
			$r=Entity::$bdd->query($query);
			$d = $r->rowCount();
			if($d==0)
				   echo '<div class="no_data col s12" style="padding: 20px;">
				   	<center><h3 class="orange-text">Aucune donnée</h3></center>
				 	  </div>';
		}


		public function pageBtn($where=NULL){
      $q = 'SELECT count(id) as ct FROM '.$this->table.($where==NULL? '': ' WHERE '.$where);
		  $r=Entity::$bdd->query($q);
		  $d=$r->fetch();

      /*echo '<input type="hidden" id="URL" value="'.$this->url.'">
        <input type="hidden" id="RESULT" value="'.$this->result.'">';*/
			for($i=0; $i< ceil($d['ct']/$this->pageSize); $i++)
				echo '<button class="btn-flat waves-effect btPage0" data="'.$i.'">'.($i+1).'</button>';
			?>
	    <!--script>
				$('.btPage0').click(function(){
					alert($(this).attr('data'));
          $.post(
            $('#URL').val(),
            {action: 'newPage', page: $(this).attr('data')},
            function(data){
              $('.'+$('#RESULT').val()).html(data);
              alert(data);
            }
          );
				});
			</script-->
	<?php
		}
	}
?>
