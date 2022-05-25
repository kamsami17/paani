<?php
  session_start();
  $_SESSION = array();
  session_destroy();
// echo sha1(md5('#paani@2021'));
//    echo sha1(md5('63274226')).'<br />';
//    echo sha1(md5('51370595')).'<br />';
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>login_title</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/home.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style>
        img { max-width: 100%; }
        button { border-radius: 15px; }
        .big_area { padding: 15px !important; } 
        .title, .app_name { text-align: center; font-size: 30px; }
        .card { border-radius: 15px; margin-top: 50px;}
        body { background: #ececec; }
        .logozone { padding: 50px !important; } 
    </style>
</head>
<body>
    <div class="row">

        <div class="col s12 m10 offset-m1 l6 offset-l3 card">
            <div class="col s12 big_area">
                <div class="col m4 descr_area" style="border-right: solid 1px #ccc;">
                    <div class="col s12 app_name">
                    <center><span style="font-size: 25px;" class="blue-text text-darken-4"><b>Paani-School</b></span></center>
                    </div>
                    <div class="col s12"><center><img style="width: 100%;" src="img/logo.jpeg" /></center></div>
                    <p class="col s12">
                        <center>
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iusto alias esse eos mollitia vero perspiciatis, temporibus repudiandae. Incidunt obcaecati alias ipsam facilis quia fugiat laborum voluptate neque, magnam nesciunt laudantium.
                        </center>
                    </p>
                    <div class="col s12">
                        <center>
                            <button onclick="$('.connect_area').hide(); $('.subscribe_area').toggle('drop'); $('.bt_sign').hide(); $('.bt_subs').toggle('drop');" class="btn bt_sign white black-text waves-effect">Inscription</button>
                            <!-- <button onclick="$('.subscribe_area').hide(); $('.connect_area').toggle('drop'); $('.bt_subs').hide(); $('.bt_sign').toggle('drop');" style="display: none;" class="btn bt_subs white black-text waves-effect">Connexion</button> -->
                        </center>
                    </div>
                </div>

                <div class="col m8 connect_area">
                    <div class="col s12 title">Bienvenue</div>
                    <!-- <div class="col s12 icon"><center><img style="height: 60px;" src="img/bee_icon.png" /></center></div> -->
                    <div class="col s12 logozone"><center><img style="height: 150px; width: 100%;" src="img/logo.jpeg" /></center>
                        <center><span style="font-size: 25px;" class="blue-text text-darken-4"><b>Paani-School</b></span></center>
                    </div>
                    <div class="col s12"><center>Connectez vous pour accéder aux ressources</center></div>
                    <form class="col s12 l10 offset-l2 connect_form">
                        <input type="hidden" name="action" value="auth" />
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required/>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">lock</i>
                            <label for="passwd">Mot de passe</label>
                            <input type="password" name="passwd" id="passwd" required/>
                        </div>
                        <div class="loader col s12" style="display: none">
                            <div class="progress">
                                <div class="indeterminate"></div>
                            </div>               
                        </div>
                        <div class="col s12">
                            <center><button type="submit" class="btn-small waves-effect waves-light orange darken-3"><i class="material-icons left">arrow_forward</i>Connexion</button></center>
                        </div>
                    </form>
                </div>

                <div class="col m8 subscribe_area" style="display: none;">
                    <div class="col s12 title">Bienvenue</div>
                    <!-- <div class="col s12 icon"><center><img style="height: 60px;" src="img/bee_icon.png" /></center></div> -->
                    <div class="col s12 logozone"><center><img style="width: 100%;" src="img/logo.png" /></center></div>
                    <div class="col s12"><center>Vous n'avez pas de compte! inscrivez vous!</center></div>
                    <form class="col s12 l10 offset-l2 subscribe_form">
                        <input type="hidden" name="action" value="subscribe" />
                        <div class="input-field col s12">
                            <i class="material-icons prefix">alternate_email</i>
                            <label for="emaili">Email</label>
                            <input type="email" name="email" id="emaili" />
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <label for="emaili">Nom</label>
                            <input type="email" name="nom" id="nomi" />
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <label for="emaili">Prénom</label>
                            <input type="email" name="prenom" id="prenomi" />
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">contact</i>
                            <label for="contacti">Contact</label>
                            <input type="text" name="contact" id="contacti" />
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <label for="uname">Username</label>
                            <input type="text" name="uname" id="uname" />
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">lock</i>
                            <label for="passwdi">Password</label>
                            <input type="password" name="passwd" id="passwdi" />
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">lock</i>
                            <label for="confirmi">Confirm</label>
                            <input type="password" name="confirm" id="confirmi" />
                        </div>
                        <div class="col s12">
                            <center><button type="submit" class="btn-small waves-effect waves-light orange darken-3"><i class="material-icons left">arrow_forward</i>Valider</button></center>
                        </div>
                    </form>
                </div>

                <!-- <div class="col m8 subscribe_area" style="display: none;"></div> -->
            </div>


        </div>

    </div>


    <script src="js/sweetalert.js" type="text/javascript"></script>
    <script src="js/login.js" type="text/javascript"></script>

</body>
</html>