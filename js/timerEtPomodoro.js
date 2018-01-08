var secondes = 0;
            var timer;
            var pause = false;
            var text = "";
            var audio = new Audio(window.location.origin + '/res/Kalimba.mp3');
			var muteMP3 = false;

			function IndiquerMinutes(min){
                secondes = min * 60;
            }

            function Chrono(){
                if (secondes > 0)
                {
                    var minutes = Math.floor(secondes/60);
                    var heures = Math.floor(minutes/60);
					var playing = false;

                    secondes -= minutes * 60;
                    if (heures > 0)
                    {
                        minutes -= heures * 60;
                        if (minutes > 0)
                        {
                            text = " " + heures + ' h ' + minutes + ' min ' + secondes + ' sec';
                        }
                        else
                        {
                            text = " " + heures + ' h ' + secondes + ' sec';
                        }
                        minutes = minutes + (heures * 60);
                        secondes = secondes + (minutes * 60) - 1;

                    }
                    else if (minutes > 0)
                    {
                        text = " " + minutes + ' min ' + secondes + ' sec';
                        secondes = secondes + (minutes * 60) - 1;
						if(minutes < 2){
							paintRed();
						}
					}
                    else
                    {
                        text = " " + secondes + ' sec';
                        secondes = secondes + (minutes * 60) - 1;
						if (secondes<50 && !playing && !muteMP3){
							playAudio();
						}
					if (secondes<40 && secondes>39)
						audio.volume+=audio.volume+1;
					if (secondes<30 && secondes>29)
						audio.volume+=audio.volume+1;
					if (secondes<20 && secondes>19)
						audio.volume+=audio.volume+1;
					if (secondes<10 && secondes>9)
						audio.volume+=audio.volume+1;

					if (secondes==1){
						stopAudio();
						playBeep();
					}
				}
               }
               else
                {
                    clearInterval(timer);
                    text = "Le temps est écoulé";
					notifyMe();
                    arreterChrono();

                }
                document.getElementById('chrono').innerHTML = text;
            }

            function DemarrerChrono(){
                timer = setInterval('Chrono()', 1000);
                document.getElementById('btn_stop').style.display = 'inline';
                document.getElementById('btn_pause').style.display = 'inline';
                document.getElementById('btn_dem').style.display = 'none';


            }
            function arreterChrono(){
                clearInterval(timer);
                document.getElementById('btn_stop').style.display = 'none';
                document.getElementById('btn_pause').style.display = 'none';
                document.getElementById('btn_dem').style.display = 'inline';

            }

			function PauseChrono(){
                if (!pause)
                {
                    pause = true;
                    clearInterval(timer);
                    text = '[EN PAUSE] ' + text;
                    document.getElementById('chrono').innerHTML = text;
                    document.getElementById('btn_stop').style.display = 'none';
                    document.getElementById('btn_pause').value = 'Reprendre';
                }
                else
                {
                    pause = false;
                    DemarrerChrono();
                    document.getElementById('btn_pause').value = 'Pause';
                }
            }

			function paintRed(){
				document.getElementById('chrono').style.color = 'red';
			}




			function playAudio(){
				audio.play();
			}

			function playBeep(){
				var beep = new Audio('./res/Beep.mp3');
				beep.play();
			}

			function stopAudio(){
				audio.pause();
				muteMP3=true;
			}

            function replayAudio(){
            	audio.play();
				muteMP3=false;

            }


			function notifyMe() {
				  // Voyons si le navigateur supporte les notifications
				  if (!("Notification" in window)) {
					alert("Ce navigateur ne supporte pas les notifications desktop");
				  }

				  // Voyons si l'utilisateur est OK pour recevoir des notifications
				  else if (Notification.permission === "granted") {
					// Si c'est ok, créons une notification
					var notification = new Notification("Game is OVER",{
					  icon: './res/lego75x75.png',
					  body: "Hey there! La timebox a expiré !",
					});
				  }

				  // Sinon, nous avons besoin de la permission de l'utilisateur
				  // Note : Chrome n'implémente pas la propriété statique permission
				  // Donc, nous devons vérifier s'il n'y a pas 'denied' à la place de 'default'
				  else if (Notification.permission !== 'denied') {
					Notification.requestPermission(function (permission) {

					  // Quelque soit la réponse de l'utilisateur, nous nous assurons de stocker cette information
					  if(!('permission' in Notification)) {
						Notification.permission = permission;
					  }

					  // Si l'utilisateur est OK, on crée une notification
					  if (permission === "granted") {
						var notification = new Notification("Game is OVER",{
					  icon: './res/lego75x75.png',
					  body: "Hey there! La timebox a expiré !",
					});
					  }
				});
	  }

  // Comme ça, si l'utlisateur a refusé toute notification, et que vous respectez ce choix,
  // il n'y a pas besoin de l'ennuyer à nouveau.
}
