<?php

   define('DB_HOST'        , "bandadegaragem.database.windows.net");
   define('DB_USER'        , "ntc");
   define('DB_PASSWORD'    , "cY7fKX#n");
   define('DB_NAME'        , "mapsindoor");
   define('DB_DRIVER'      , "sqlsrv");
  
   
   class Conexao
   {
	  private static $connection;
	 
	  private function __construct(){}
	 
	  public static function getConnection() {
	 
		  $pdoConfig  = DB_DRIVER . ":". "Server=" . DB_HOST . ";";
		  $pdoConfig .= "Database=".DB_NAME.";";
		  
		  try {
			  if(!isset($connection)){
				  $connection =  new PDO($pdoConfig, DB_USER, DB_PASSWORD);
				  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  }
			  return $connection;
		  } catch (PDOException $e) {
			  $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
			  $mensagem .= "\nErro: " . $e->getMessage();
			  throw new Exception($mensagem);
		  }
	  }
   }
   

//     <!-- <script> 
//         var source='localhost/Aluno';
//     this.serverRequest = $.get(source, function (result) {
//         console.log(result)
//         if ( typeof(result) === 'string') {
//         result = JSON.parse (result);
//         }
        
//     }.bind(this));
//    </script> -->
 
//     // $result = "Manhã";

   try{
	$result = "Manhã";					
	$Conexao    = Conexao::getConnection();
	$query      = $Conexao->prepare("	SELECT DISTINCT
                                        NomeCurso = LTRIM(RTRIM(C.Nome)) 
                                    FROM 
                                        Turma T
                                        INNER JOIN Curso C
                                        ON T.curso = C.id
                                    WHERE
                                        T.NomeExibicao like :result"); 
    $query->bindValue(':result', '%'. $result . '%');
    $query->execute();                                    
	$produtos  = $query->fetchAll();

	}catch(Exception $e){
		echo $e->getMessage();
		exit;
	}


	foreach($produtos as $p) {
		$dados[] = array($p['NomeCurso']);
	}
	$json_str = json_encode($dados, JSON_UNESCAPED_UNICODE);
	echo "$json_str";
?>

