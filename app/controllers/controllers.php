<?php

//Silex Website part
$app->match('/', function() use ($app) {
    return $app['twig']->render('home.html.twig');
})->bind('home');

$app->match('/team', function() use ($app) {
    return $app['twig']->render('team.html.twig', array(
    ));
})->bind('team');

$app->match('/vision', function() use ($app) {
    return $app['twig']->render('vision.html.twig', array(
    ));
})->bind('vision');

$app->match('/basicInfo', function() use ($app) {
    return $app['twig']->render('basicInfo.html.twig', array(
    ));
})->bind('basicInfo');

$app->match('/timerEtPomodoro', function() use ($app) {
    return $app['twig']->render('timerEtPomodoro.html.twig', array(
    ));
})->bind('timerEtPomodoro');

$app->match('/squareGameInfo', function() use ($app) {
    return $app['twig']->render('squareGameInfo.html.twig', array(
    ));
})->bind('squareGameInfo');


$app->match('/squareGame/startGame', function() use ($app) {
    $request = $app['request'];
    if ($request->getMethod() == 'POST') {
        $post = $request->request;
        if ($post->has('nomSession')) {
            $app['model']->nouvelleSession($post->get('nomSession'));
            $app['session']->set('nomSession', $post->get('nomSession'));
            return $app->redirect($app['url_generator']->generate('play'));
        }
    }
    return $app['twig']->render('squareGame/startGame.html.twig', array(
    ));
})->bind('startGame');

$app->match('/squareGame/play', function() use ($app) {
    $request = $app['request'];
    if ($request->getMethod() == 'POST') {
        return $app->redirect($app['url_generator']->generate('close'));
    }
    return $app['twig']->render('squareGame/play.html.twig', array(
    ));
})->bind('play');


$app->match('/squareGame/close', function() use ($app) {
    $request = $app['request'];
    if ($request->getMethod() == 'POST') {
        $post = $request->request;
        if ($post->has('nomSession')) {
            $app['model']->fermeSession($post->get('nomSession'),$post->get('acm2'));
            $app['session']->set('nomSession', $post->get('nomSession'));
            return $app->redirect($app['url_generator']->generate('report'));
        }
    }
    return $app['twig']->render('squareGame/close.html.twig', array(
    ));
})->bind('close');


$app->match('/squareGame/report', function() use ($app) {
     $request = $app['request'];
     if ($request->getMethod() == 'GET') {
        $nomSession=$request->query->get('nomSession');
        if ($nomSession== null)
            $nomSession= $app['session']->get('nomSession');
         else
            $app['session']->set('nomSession',$nomSession);
    }
    $resultatSession    = $app['model']->recupereEstimationsSession($nomSession);
    $mesures            = $app['model']->recupereMesureSession($nomSession);
    $syntheseSession    = $app['model']->calculeSyntheseSession($resultatSession, $mesures, $nomSession);
      return $app['twig']->render('squareGame/report.html.twig', array("resultatSession" => $resultatSession, "syntheseSession" => $syntheseSession
    ));
})->bind('report');


$app->match('/squareGame/sessions', function() use ($app) {
    $sess    = $app['model']->listeSessions();
    //echo "controler.match_squareGameSessions" + var_dump($sess);
    return $app['twig']->render('squareGame/sessions.html.twig', array("sess" => $sess
    ));
})->bind('sessions');

$app->match('/squareGame/players/loginSession', function() use ($app) {
    $request = $app['request'];
    if ($request->getMethod() == 'POST') {
        $post = $request->request;
        if ($post->has('nomSession') && $post->has('nickname')) {
            $app['model']->nouveauJoueur($post->get('nomSession'), $post->get('nickname'));
            $app['session']->set('nickname', $post->get('nickname'));
            $app['session']->set('nomSession', $post->get('nomSession'));
            return $app->redirect($app['url_generator']->generate('absoluteEstimation'));//squareGame/players/
        }
    }
    return $app['twig']->render('squareGame/players/loginSession.html.twig', array(
    ));
})->bind('loginSession');

$app->match('/squareGame/players/absoluteEstimation', function() use ($app) {
    $request = $app['request'];
    if ($request->getMethod() == 'POST') {
        $post = $request->request;
        if ($post->has('acm2') && $post->has('bcm2') && $post->has('ccm2')) {
             $app['session']->set('acm2', $post->get('acm2'));
             $app['session']->set('bcm2', $post->get('bcm2'));
             $app['session']->set('ccm2', $post->get('ccm2'));

            $app['model']->mesureCm2Joueur($post->get('acm2'), $post->get('bcm2'), $post->get('ccm2'), $app['session']->get('nomSession'), $app['session']->get('nickname'));
            return $app->redirect($app['url_generator']->generate('relativeEstimation'));
        }
    }
    return $app['twig']->render('squareGame/players/absoluteEstimation.html.twig', array(
    ));
})->bind('absoluteEstimation');

$app->match('/squareGame/players/relativeEstimation', function() use ($app) {
    $request = $app['request'];
    if ($request->getMethod() == 'POST') {
        $post = $request->request;
        if ($post->has('aRel') && $post->has('bRel') && $post->has('cRel')) {
             $app['session']->set('aRel', $post->get('aRel'));
             $app['session']->set('bRel', $post->get('bRel'));
             $app['session']->set('cRel', $post->get('cRel'));

            $app['model']->mesureRelJoueur($post->get('aRel'), $post->get('bRel'), $post->get('cRel'), $app['session']->get('nomSession'), $app['session']->get('nickname'));
            return $app->redirect($app['url_generator']->generate('results'));
        }
    }
    return $app['twig']->render('squareGame/players/relativeEstimation.html.twig', array(
    ));
})->bind('relativeEstimation');

$app->match('/squareGame/players/results', function() use ($app) {
    $nomSession = $app['session']->get('nomSession');
    $resultatSession    = $app['model']->recupereEstimationsSession($nomSession);
    $mesures            = $app['model']->recupereMesureSession($nomSession);
    $syntheseSession    = $app['model']->calculeSyntheseSession($resultatSession, $mesures, $nomSession);
    return $app['twig']->render('squareGame/players/results.html.twig', array("syntheseSession" => $syntheseSession
    ));
})->bind('results');

//rest api part
//compte le nombre d'utilisateurs connectés à la session
$app->get('/api/user/count/{idsession}', function ($idsession) use ($app) {

	$nbuser = $app['model']->countJoueurs($idsession);
	if (!isset($nbuser)) {
  		$app->abort(500, 'Internal Server Error');
	}
	$responseData = $nbuser;
    return $app->json(array('nombreUtilisateursConnectes' => $responseData));
})->bind('api_user_count');

//compte le nombre d'utilisateurs ayant renseigné les estimations absolues
$app->get('/api/user/count/{idsession}/absolute', function ($idsession) use ($app) {

	$nbuser = $app['model']->countJoueursAbsolute($idsession);
	if (!isset($nbuser)) {
  		$app->abort(500, 'Internal Server Error');
	}
	$responseData = $nbuser;
    return $app->json(array('nombreJoueursAbsolute' => $responseData));
})->bind('api_user_count_absolute');


//compte le nombre d'utilisateurs ayant renseigné les estimations relatives
$app->get('/api/user/count/{idsession}/relative', function ($idsession) use ($app) {

	$nbuser = $app['model']->countJoueursRelative($idsession);
	if (!isset($nbuser)) {
  		$app->abort(500, 'Internal Server Error');
	}
	$responseData = $nbuser;
    return $app->json(array('nombreJoueursRelative' => $responseData));


})->bind('api_user_count_relative');
