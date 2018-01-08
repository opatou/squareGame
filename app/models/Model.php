<?php

class Model
{
    protected $pdo;
    protected $aRelativeArea = 1;
    protected $bRelativeArea = 2;
    protected $cRelativeArea = 5;
    
    //Constructeur -> initialisation connexion SGBD
    public function __construct(array $config)
    {
        try {
            if ($config['engine'] == 'mysql') {
                $this->pdo = new \PDO(
                    'mysql:host='.$config['host'].';dbname='.$config['database'],
                    $config['user'],
                    $config['password']
                );
                $this->pdo->exec('SET CHARSET UTF8');
            }
        } catch (\PDOException $error) {
            throw new ModelException('Unable to connect to database');
        }
    }
    
    //Interface SGBD bas niveau
    protected function prepare($sql)
    {
        return $this->pdo->prepare($sql,
        array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL)
        );
    }

    protected function execute(\PDOStatement $query, array $variables = array())
    {
        if (!$query->execute($variables)) {
            $errors = $query->errorInfo();
            throw new ModelException($errors[2]);
        }

        return $query;
    }
    
    protected function fetchOne(\PDOStatement $query)
    {
        if ($query->rowCount() != 1) {
            return false;
        } else {
            return $query->fetch();
        }
    }



    //Fonction Mathématiques
    function ecarttype($array)
    {
        $nbdecimals=2;
        $nbelements=count($array);
        if (is_array($array) && $nbelements>0)
        {
            //moyenne des valeurs
            reset($array);
            $somme=0;
            
            foreach ($array as $value) {
                $somme += floatval($value);
            }
            $moyenne = $somme/$nbelements;
            //numerateur
            reset($array);
            $sigma=0;
            foreach ($array as $value) {
                $sigma += pow((floatval($value)-$moyenne),2);
                //echo (var_dump($sigma));
            }
            //echo("\nfin boucle sigma = ");
            //echo (var_dump($sigma));
         
            //denominateur = $nbelement
            $ecarttype = sqrt($sigma/$nbelements);
            return round($ecarttype);
        }
        else
        {
            return false;
        }
    }

    
    //Calcul des statistiques de la session
    function ecarttypeALAMesure($array, $mesure)
     {
        $nbdecimals=2 ;
        $nbelements = count($array);
        if (is_array($array) && $nbelements>0)
        {
            $nbelement=count($array);
            //numerateur
            reset($array);
            $sigma=0;
            foreach ($array as $value) {
                $sigma += pow((floatval($value)-$mesure),2);
            }
            //denominateur = $nbelement
            $ecarttype = sqrt($sigma/$nbelement);
            return round($ecarttype, $nbdecimals);
        }
        else
        {
            return false;
        }
    }
    
    protected function calculeEcartType($eacmA,$ebcmA,$eccmA,$eaRelA,$ebRelA,$ecRelA)
    {
        $eacm = $this->ecarttype($eacmA);
        $ebcm = $this->ecarttype($ebcmA);
        $eccm = $this->ecarttype($eccmA);
        $eaRel = $this->ecarttype($eaRelA);
        $ebRel = $this->ecarttype($ebRelA);
        $ecRel = $this->ecarttype($ecRelA);
        $tableEType = array($eacm,$ebcm,$eccm,$eaRel,$ebRel,$ecRel);
        echo(var_dump($tableEType));
        echo ("ebcm");
        echo(var_dump($ebcm));
        return $tableEType;
    }

    protected function calculeEcartTypeALAMesure($eacmA,$ebcmA,$eccmA,$eaRelA,$ebRelA,$ecRelA,$mesures)
    {
        $eacm = $this->ecarttypeALAMesure($eacmA,$mesures[0]);
        $ebcm = $this->ecarttypeALAMesure($ebcmA,$mesures[1]);
        $eccm = $this->ecarttypeALAMesure($eccmA,$mesures[2]);
        $eaRel = $this->ecarttypeALAMesure($eaRelA,$this->aRelativeArea);
        $ebRel = $this->ecarttypeALAMesure($ebRelA,$this->bRelativeArea);
        $ecRel = $this->ecarttypeALAMesure($ecRelA,$this->cRelativeArea);
        return array($eacm,$ebcm,$eccm,$eaRel,$ebRel,$ecRel);
    }

    protected function calculeMoyenne($values)
    {
        $nbelements = count($values);
        if ($nbelements>0){
            $moyenne=0;
            $somme=0;
            for ($i=0; $i<sizeof($values);$i++) {
                $somme += $values[$i];
            }
            $moyenne= round($somme/$i,2); 
            return $moyenne;
        }
        else{
            return false;
        }
    } 
    
    protected function  calculeMoyennes($eacmA,$ebcmA,$eccmA,$eaRelA,$ebRelA,$ecRelA)
    {
      $moy_acm = $this->calculeMoyenne($eacmA);
      $moy_bcm = $this->calculeMoyenne($ebcmA);
      $moy_ccm = $this->calculeMoyenne($eccmA);
      $moy_aRel= $this->calculeMoyenne($eaRelA);
      $moy_bRel= $this->calculeMoyenne($ebRelA);
      $moy_cRel= $this->calculeMoyenne($ecRelA);
   
        return array($moy_acm,$moy_bcm,$moy_ccm,$moy_aRel,$moy_bRel,$moy_cRel);
    }

   
    
    //Interface SGBD Metier
    private function sessionExiste($nomSession){
        $query = $this->pdo->prepare('SELECT COUNT(*) AS sessionExiste FROM sessionsquaregame WHERE nomSession = ?');
        $resultat = $this->execute($query, array($nomSession));
        return $resultat->fetchColumn();
    }
    
    
    
    public function nouvelleSession($nomSession)
    {    
        
        if ($this->sessionExiste($nomSession)==1)
            return;
        //echo (var_dump($this->sessionExiste($nomSession)));
        $query = $this->pdo->prepare('INSERT INTO sessionsquaregame (nomSession, dateOuverture) VALUES (?, ?)');
            date_default_timezone_set('Europe/Paris');
            $dateOuverture = date('Y-m-d H:i:s');
        $this->execute($query, array($nomSession, $dateOuverture));
    }

    public function fermeSession($nomSession,$acm)
    {
       // echo var_dump($nomSession, $acm, $bcm,$ccm);
        $query = $this->pdo->prepare('UPDATE sessionsquaregame
            SET dateFermeture = ?,
            acm2 = ?,
            bcm2 = ?,
            ccm2 = ?
            WHERE nomSession = ? AND dateFermeture IS NULL');
            date_default_timezone_set('Europe/Paris');
            $dateFermeture = date('Y-m-d H:i:s');
        $this->execute($query, array($dateFermeture, $acm, $acm*2, $acm*5, $nomSession));
    }

    public function nouveauJoueur($nomSession, $nickname)
    {
        $query = $this->pdo->prepare('INSERT INTO joueursquaregame (nomSession, nickname)
            VALUES (?, ?)');
        $this->execute($query, array($nomSession, $nickname));
    }

    public function countJoueurs($nomSession){
        $query = $this->pdo->prepare('SELECT COUNT(*) AS nbjoueurs FROM joueursquaregame WHERE nomSession = ?');
        $resultat = $this->execute($query, array($nomSession));
        return $resultat->fetchColumn();
    }

    public function countJoueursAbsolute($nomSession){
        $query = $this->pdo->prepare('SELECT COUNT(*) AS nbjoueursabsolute FROM joueursquaregame
            WHERE nomSession = ?
            AND acm2 IS NOT NULL
            AND bcm2 IS NOT NULL
            AND ccm2 IS NOT NULL');

        $resultat = $this->execute($query, array($nomSession));
        return $resultat->fetchColumn();
    }

        public function countJoueursrelative($nomSession){
        $query = $this->pdo->prepare('SELECT COUNT(*) AS nbjoueursabsolute FROM joueursquaregame
            WHERE nomSession = ?
            AND arel IS NOT NULL
            AND brel IS NOT NULL
            AND crel IS NOT NULL');

        $resultat = $this->execute($query, array($nomSession));
        return $resultat->fetchColumn();
    }

    public function mesureCm2Joueur($acm2, $bcm2, $ccm2, $nomSession, $nickname)
    {
        $query = $this->pdo->prepare('UPDATE joueursquaregame
            SET acm2 = ?,
            bcm2 = ?,
            ccm2 = ?
            WHERE nomSession = ? AND nickname = ?');
        $this->execute($query, array($acm2, $bcm2, $ccm2, $nomSession, $nickname));
    }

    public function mesureRelJoueur($aRel, $bRel, $cRel, $nomSession, $nickname)
    {
        $query = $this->pdo->prepare('UPDATE joueursquaregame
            SET aRel = ?,
            bRel = ?,
            cRel = ?,
            dateVote = ?
            WHERE nomSession = ? AND nickname = ?');
        date_default_timezone_set('Europe/Paris');
        $dateVote = date('Y-m-d H:i:s');
        $this->execute($query, array($aRel, $bRel, $cRel, $dateVote, $nomSession, $nickname));
    }

    public function recupereEstimationsSession( $nomSession)
    {
        $query              = $this->pdo->prepare('SELECT * FROM joueursquaregame WHERE nomSession = ?  AND ACM2 > 0 AND BCM2 > 0 AND CCM2 > 0 AND AREL > 0 AND BREL > 0 AND CREL > 0');
        $resultatSession    = $this->execute($query, array($nomSession));
        $i=0;
        $res = array();
        foreach ($resultatSession as $elem){
            $res[$i]['nickname']=$elem[0];
            $res[$i]['nomSession']=$elem[1];
            $res[$i]['acm2']=$elem[2];
            $res[$i]['bcm2']=$elem[3];
            $res[$i]['ccm2']=$elem[4];
            $res[$i]['aRel']=$elem[5];
            $res[$i]['bRel']=$elem[6];
            $res[$i]['cRel']=$elem[7];
            $i++;
        }
        return $res;
    }

    public function recupereMesureSession($nomSession)
    {
        //echo var_dump ($nomSession);
        $query              = $this->pdo->prepare('SELECT * FROM sessionsquaregame WHERE nomSession = ?  ');
        $mesures            = $this->execute($query, array($nomSession));
        $mes = array();
        foreach ($mesures as $elem){
            $mes[0]=$elem[3];
            $mes[1]=$elem[4];
            $mes[2]=$elem[5];
        }
        return $mes;
    }


    function compterNBConnectes($nomSession){
         $query              = $this->pdo->prepare('SELECT COUNT(*) FROM joueursquaregame WHERE nomSession = ? ');
         $resultatNBJoueursSession    = $this->execute($query, array($nomSession));
        return $resultatNBJoueursSession[0][0];


}

    public function rechercheMinimum($table){
        if (count($table)>0){
            $min = $table[0];
            for ($i=1; $i<sizeof($table);$i++) {
                if ($table[$i]<$min)
                    $min=$table[$i];
            }
            return $min;
        }
        else return 0;
    }
    
    public function rechercheMaximum($table){
        if (count($table)>0){
            $max = $table[0];
            for ($i=1; $i<sizeof($table);$i++) {
                if ($table[$i]>$max) $max=$table[$i];
            }
            return $max;
        }
        else return 0;

    }

       //METIER : SYNTHESE DE LA SESSION D'ESTIMATION
    public function calculeSyntheseSession($resultatSession,$mesures,$session)
    {

        $i=0;

        $min = array(0,0,0,0,0,0);
        $max = array(0,0,0,0,0,0);
        $moy = array(0,0,0,0,0,0);

        $eacm = array();
        $ebcm = array();
        $eccm = array();
        $eaRel = array();
        $ebRel = array();
        $ecRel = array();
        
        if (count($resultatSession)>0){
            foreach ($resultatSession as $elem)
            {
                $eacm[$i] = $elem['acm2'];;
                $ebcm[$i] = $elem['bcm2'];
                $eccm[$i] = $elem['ccm2'];
                $eaRel[$i] = $elem['aRel'];
                $ebRel[$i] = $elem['bRel'];
                $ecRel[$i] = $elem['cRel'];

                $i++;

            }
        }else return false;
        //echo var_dump($eacm);
        $min = array();
        $min[0] = $this->rechercheMinimum($eacm);
        $min[1] = $this->rechercheMinimum($ebcm);
        $min[2] = $this->rechercheMinimum($eccm);
        $min[3] = $this->rechercheMinimum($eaRel);
        $min[4] = $this->rechercheMinimum($ebRel);
        $min[5] = $this->rechercheMinimum($ecRel);
        
        $max = array();
        $max[0] = $this->rechercheMaximum($eacm);
        $max[1] = $this->rechercheMaximum($ebcm);
        $max[2] = $this->rechercheMaximum($eccm);
        $max[3] = $this->rechercheMaximum($eaRel);
        $max[4] = $this->rechercheMaximum($ebRel);
        $max[5] = $this->rechercheMaximum($ecRel);
        
        $etype = $this->calculeEcartType($eacm,$ebcm,$eccm,$eaRel,$ebRel,$ecRel);
        $moy = $this->calculeMoyennes($eacm,$ebcm,$eccm,$eaRel,$ebRel,$ecRel);
        $etypeALAMesure = $this->calculeEcartTypeALAMesure($eacm,$ebcm,$eccm,$eaRel,$ebRel,$ecRel,$mesures);
        
        echo ("etype_mesure_C" + var_dump($etypeALAMesure[2]) + " ... etype_moyenne " + var_dump( $etype[2]));
        
        $syntheseSession[] = array('valeur' => 'mesures', 'acm' => $mesures[0], 'bcm'=> $mesures[1], 'ccm'=>  $mesures[2],'aRel' => 1, 'bRel' => 2 ,'cRel'=> 5);
        $syntheseSession[] = array('valeur' => 'moyenne', 'acm' => $moy[0], 'bcm'=> $moy[1], 'ccm'=>  $moy[2],'aRel' => $moy[3], 'bRel' => $moy[4] ,'cRel'=> $moy[5]);
        $syntheseSession[] = array('valeur' => 'Ecart Type à la mesure', 'acm' => $etypeALAMesure[0], 'bcm'=> $etypeALAMesure[1], 'ccm'=> $etypeALAMesure[2],'aRel' => $etypeALAMesure[3], 'bRel' =>                            $etypeALAMesure[4] ,'cRel'=> $etypeALAMesure[5]);
        $syntheseSession[] = array('valeur' => 'Ecart Type à la moyenne', 'acm' => $etype[0], 'bcm'=> $etype[1], 'ccm'=> $etype[2],'aRel' => $etype[3], 'bRel' => $etype[4] ,'cRel'=> $etype[5]);
        $syntheseSession[] = array('valeur' => 'minimum', 'acm' => $min[0], 'bcm'=> $min[1], 'ccm'=>  $min[2],'aRel' => $min[3], 'bRel' => $min[4] ,'cRel'=> $min[5]);
        $syntheseSession[] = array('valeur' => 'maximum', 'acm' => $max[0], 'bcm'=> $max[1], 'ccm'=>  $max[2],'aRel' => $max[3], 'bRel' => $max[4] ,'cRel'=> $max[5]);


        return $syntheseSession;
    }

    public function listeSessions()
    {
        $query              = $this->pdo->prepare('SELECT * FROM sessionsquaregame ORDER BY dateOuverture DESC');
        $sessionL            = $this->execute($query);
        //$sess = array();
        $i=0;
        foreach ($sessionL as $elem){
            $sess[$i]['nomSession']=$elem[0];
            $sess[$i]['dateOuverture']=$elem[1];
            $sess[$i]['dateFermeture']=$elem[2];
            $sess[$i]['acm2']=$elem[3];
            $sess[$i]['bcm2']=$elem[4];
            $sess[$i]['ccm2']=$elem[5];
           $i++;
            // echo "session Model.listeSession() : " + var_dump( $sess['nomSession']);
        }
        return $sess;
    }

/*    public function recupeMesureSession($nomSession)
    {
         echo "--recupeMesureSession 1";
        $query              = $this->pdo->prepare('SELECT acm2 FROM sessionsquaregame WHERE nomSession = ? ');
        echo  "--recupeMesureSession 2";
        $resultatSession    = $this->execute($query, array($nomSession));
         echo "--recupeMesureSession 3";

        //$mesuresSession= $resultatSession[0]['acm2'];
        return $resultatSession;
    }*/

   
/*  //not used and do not calculate inside this method : not SRP
    public function recupereAll()
    {


        $query              = $this->pdo->prepare('SELECT * FROM joueursquaregame WHERE ACM2 > 0 AND BCM2 > 0 AND CCM2 > 0 AND AREL > 0 AND BREL > 0 AND CREL > 0 ;');
        $resultatSession    = $this->execute($query);
        $nomSession=null;
        $i=0;
        foreach ($resultatSession as $elem)
        {

            if ($nomSession == null) {
                $nomSession=$elem[1];
                $query              = $this->pdo->prepare('SELECT acm2 FROM sessionsquaregame WHERE nomSession = ? ');
                $resultSetSession    = $this->execute($query, array($nomSession));
               foreach($resultSetSession as $elemCM2)
               {
                    $mesureACM2Session= $elemCM2[0]['acm2'];
                }
            }

            echo var_dump($elem[1]);
            $res[$i]['nickname']=$elem[0];
            $res[$i]['nomSession']=$elem[1];

            if ($mesureACM2Session==null || $mesureACM2Session==0 ){
                $res[$i]['acm2']=$elem[2];// pour réduire à une échelle commune
                $res[$i]['bcm2']=$elem[3];
                $res[$i]['ccm2']=$elem[4];
           }else {
                $res[$i]['acm2']=$elem[2]/$mesureACM2Session;// pour réduire à une échelle commune
                $res[$i]['bcm2']=$elem[3]/$mesureACM2Session;
                $res[$i]['ccm2']=$elem[4]/$mesureACM2Session;
            }
            $res[$i]['aRel']=$elem[5];
            $res[$i]['bRel']=$elem[6];
            $res[$i]['cRel']=$elem[7];
            $i++;
        }

        return $res;

    }
*/

    public function syntheseAllSessions()
    {

        $i=0;
        $moy = array(0,0,0,0,0,0);
        $eacm = array();
        $ebcm = array();
        $eccm = array();
        $eaRel = array();
        $ebRel = array();
        $ecRel = array();

        $resultatSession = $this->recupereAll();

        foreach ($resultatSession as $elem) {
            $eacm[$i] = $elem['acm2'];//$elem[2];
            $ebcm[$i] = $elem['bcm2'];
            $eccm[$i] = $elem['ccm2'];
            $eaRel[$i] = $elem['aRel'];
            $ebRel[$i] = $elem['bRel'];
            $ecRel[$i] = $elem['cRel'];

            $i++;
        }
        $etype = $this->calculeEcartType($eacm,$ebcm,$eccm,$eaRel,$ebRel,$ecRel);
        $moy = $this->calculeMoyenne($eacm,$ebcm,$eccm,$eaRel,$ebRel,$ecRel);

        $syntheseSession[] = array('valeur' => 'moyenne', 'acm' => $moy[0], 'bcm'=> $moy[1], 'ccm'=>  $moy[2],'aRel' => $moy[3], 'bRel' => $moy[4] ,'cRel'=> $moy[5]);
        $syntheseSession[] = array('valeur' => 'Ecart Type à la moyenne', 'acm' => $etype[0], 'bcm'=> $etype[1], 'ccm'=> $etype[2],'aRel' => $etype[3], 'bRel' => $etype[4] ,'cRel'=> $etype[5]);

        return $syntheseSession;
    }

}
