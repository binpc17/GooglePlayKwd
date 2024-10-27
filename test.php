<?php 
session_start();
include "partials/header.php";
include_once "config/core.php";
            // set page title
$page_title = "Deposer travail";
            // include classes
include_once 'config/database.php';
include_once 'objects/correctiontravail.php';
include_once 'objects/etudiant.php';
include_once 'objects/sujetTravail.php';
include_once "libs/php/utils.php";
include_once "config/connect.php";
require 'PHPMailer-master/PHPMailerAutoload.php';


// Download library -> https://www.primotexto.com/api/librairies/latest-php.asp
   // require_once 'primotexto-api-php/baseManager.class.php';

    


$database = new Database();
$db = $database->getConnection();
            // pass connection to objects
$correctionTravail = new CorrectionTravail($db);
$sujetTravail = new SujetTravail($db);
$etudiant = new Etudiant($db);
$sujetTravail = new SujetTravail($db);

$error_message='';
$success_message='';

/*
if (!isset($_SESSION['idUser'])){
    header('location:login.php');
}*/

if (!isset($_COOKIE['idUser'])){
    header('location:login.php');
}
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.'); 
$sujetTravail->id=$id;
$sujetTravail->readOne();
$dest = isset($_GET['dest']) ? $_GET['dest'] : die('ERROR: missing DEST.');
$uLevel=$_COOKIE['niveauAcces'];
$idUser=0;
$name=$_COOKIE['nom'];
$postname=$_COOKIE['postNom'];
$prename=$_COOKIE['prenom'];

$lastPromotion=time();

/*Load subjetTirle*/
 $resSubject=mysqli_query($mysqli,"SELECT sj.titre,u.mail,u.nom FROM sujettravail sj INNER JOIN utilisateur AS u ON u.idUser=sj.Etudiant_Utilisateur_idUser WHERE sj.id=$id");
 //$subjectName=mysqli_fetch_array($resSubject)['titre'];
  $subMail=mysqli_fetch_array($resSubject);
  
/*Load target user Name*/
 $resTarget=mysqli_query($mysqli,"SELECT nom,postNom,mail,level FROM utilisateur WHERE idUser=$dest");
 $targetName=mysqli_fetch_array($resTarget);
if($_POST){
    

    
    if(!empty($_POST['commentaire']) && empty($_FILES["fichierTrav"]["name"] )){
        $result_message ="";
       //>>> if($correctionTravail->create()){
       $varTest=12;
        if($varTest=='12'){
            $success_message="Le message a été envoyé .";
            
            /*try Notify Target */
                                               // $to=$targetName['mail'];
                                                $to="yvettekalimumbalo@gmail.com";
         
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8'; 
    $mail ->IsSmtp();
   $mail ->SMTPDebug =0;
   $mail ->SMTPAuth =true;
   $mail ->SMTPSecure ='ssl';
 
    /*$mail ->Host ="mail.fstaulpgl.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@fstaulpgl.com";
   $mail ->Password="ulpgl@SgtGoma";
   $mail ->SetFrom("info@fstaulpgl.com","FSTA ULPGL");*/ 
   
   $mail ->Host ="hp294.hostpapa.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@egozola.com";
   $mail ->Password="infoEgozola1422";
   
   $mail ->SetFrom("info@egozola.com","SGT ULPGL");
   
   // HTML body
            $mail->Subject = "SGT-iMessage Nouveau message pour: {$subMail['titre']}";
            $body  = "<font size=\"4\"> Bonjour ". $targetName['level']." ".$targetName['preNom']." ".$targetName['nom']." !</font>, <p>";
            $body .= "Sujet de TFE/TFC >> <font size=\"4\"> {$subMail['titre']} </font><p>";
            $body .= "-> Vous avez réçu un Nouveau message: [ {$_POST['commentaire']} ]. <br> Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl} <p>";
            $body .= "-------------------------------<p>";
            $body .= "SGT Team, <br>";
          
                // Plain text body (for mail clients that cannot read HTML)
    $text_body  ="<font size=\"4\"> Bonjour {$targetName['level']} {$targetName['nom']}! </font>, \n\n";
    $text_body .= "Sujet de TFE/TFC:>> {$subMail['titre']}\n\n";
   $text_body .= "-> Vous avez réçu un Nouveau message: [ {$_POST['commentaire']} ]. \n\n Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl}  \n\n";
    $text_body .="-------------------------------\n\n";
    $text_body .= "SGT Teams,\n\n";
    
    $mail->Body    = $body;
    $mail->AltBody = $text_body;
            $mail->AddAddress($to);
     
            if($mail->Send()){
                       }
          
                       
                                    /*try notify Student**************************************** */
                       if($uLevel=='etudiant'){
                       // $toStudent=$subMail['mail'];
                        $toStudent="binpc17@gmail.com";
                           $mail = new PHPMailer();
    $mail->CharSet='UTF-8'; 
    $mail ->IsSmtp();
   $mail ->SMTPDebug =0;
   $mail ->SMTPAuth =true;
   $mail ->SMTPSecure ='ssl';
 
    /*$mail ->Host ="mail.fstaulpgl.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@fstaulpgl.com";
   $mail ->Password="ulpgl@SgtGoma";
   $mail ->SetFrom("info@fstaulpgl.com","FSTA ULPGL"); */
   
   $mail ->Host ="hp246.hostpapa.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@egozola.com";
   $mail ->Password="infoEgozola1422";
   
   $mail ->SetFrom("info@egozola.com","SGT ULPGL");
   
   
  $mail->Subject = "SGT-iMessage Message envoyé à : {$targetName['preNom']} {$targetName['nom']}";
            $body  = "<font size=\"4\"> Bonjour ". $_COOKIE['nom']." !</font>, <p>";
            $body .= "Sujet de TFE/TFC >> <font size=\"4\"> {$subMail['titre']} </font><p>";
            $body .= "-> Votre message a été envoyé avec succès: [ {$_POST['commentaire']} ]. <br> Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl} <p>";
            $body .= "-------------------------------<p>";
            $body .= "SGT Team, <br>";
            
                // Plain text body (for mail clients that cannot read HTML)
    $text_body  ="<font size=\"4\"> Bonjour ". $_COOKIE['nom']."! </font>, \n\n";
    $text_body .= "Sujet de TFE/TFC:>> {$subjectName}\n\n";
   $text_body .= "-> Votre message a été envoyé avec succès: [ {$_POST['commentaire']} ]. \n\n Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl}  \n\n";
    $text_body .="-------------------------------\n\n";
    $text_body .= "SGT Teams,\n\n";
    
    $mail->Body    = $body;
    $mail->AltBody = $text_body;
            $mail->AddAddress($toStudent);
     
            if($mail->Send()){
                       }
                       }
        }else{
            $error_message="Impossible d'envoyer le message.";
        }

        
    }else if(!empty($_POST['commentaire']) && !empty($_FILES["fichierTrav"]["name"])){
            $file_upload_error_messages="";
            $result_message="";
                        // set product property values
            $file_name=$_FILES["fichierTrav"]["name"];
            $file_extension=strrchr($file_name,".");

            $file_tmp_name=$_FILES["fichierTrav"]["tmp_name"];
            
            $file_DB=$idUser."_Travail_From_".$name."_".$postname."_".$prename."_".$lastPromotion.$file_extension;
            
            //$file_DB=$idUser."_Travail_".$lastPromotion.$file_extension;
            $file_dest='uploads/'.$file_DB;

            
            $extensions_autorisees=array(".pdf",".png",".PNG",".jpeg",".JPEG",".jpg",".JPG",".PDF",".docx",".DOCX");
            if(in_array($file_extension, $extensions_autorisees)){
                if(move_uploaded_file($file_tmp_name, $file_dest)){
                    $success_message="Fichier uploadé avec success.";

             
       //>>> if($correctionTravail->create()){
       $varTest=12;
        if($varTest=='12'){

                        $success_message="Le message joint a été envoyé .";
                        
                                /*try Notify Target */
                                                          //>> $to=$targetName['mail'];
                                                          $to="yvettekalimumbalo@gmail.com";
         
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8'; 
    $mail ->IsSmtp();
   $mail ->SMTPDebug =0;
   $mail ->SMTPAuth =true;
   $mail ->SMTPSecure ='ssl';
 
    /*$mail ->Host ="mail.fstaulpgl.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@fstaulpgl.com";
   $mail ->Password="ulpgl@SgtGoma";
   $mail ->SetFrom("info@fstaulpgl.com","FSTA ULPGL"); */
   
   //$mail ->Host ="hp294.hostpapa.com";
   //$mail ->Port = 465; // or 587
   //$mail ->IsHTML(true);
   //$mail ->Username="info@egozola.com";
   //$mail ->Password="infoEgozola1422";
   //$mail ->SetFrom("info@egozola.com","SGT ULPGL");
   
   $mail ->Host ="mail.fstaulpgl.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@fstaulpgl.com";
   $mail ->Password="ulpgl@SgtGoma";
   $mail ->SetFrom("info@fstaulpgl.com","FSTA ULPGL");
   
   
   

   // HTML body
            $mail->Subject = "SGT-iMessage Nouveau message pour: {$subMail['titre']}";
            $body  = "<font size=\"4\"> Bonjour ". $targetName['level']." ".$targetName['preNom']." ".$targetName['nom']." !</font>, <p>";
            $body .= "Sujet de TFE/TFC >> <font size=\"4\"> {$subMail['titre']} </font><p>";
            $body .= "-> Vous avez réçu un Nouveau message: [ {$_POST['commentaire']} ]. <br> Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl} <p>";
            $body .= "-------------------------------<p>";
            $body .= "SGT Team, <br>";  
            
                            // Plain text body (for mail clients that cannot read HTML)
    $text_body  ="<font size=\"4\"> Bonjour {$targetName['level']} {$targetName['nom']}! </font>, \n\n";
    $text_body .= "Sujet de TFE/TFC:>> {$subMail['titre']}\n\n";
   $text_body .= "-> Vous avez réçu un Nouveau message: [ {$_POST['commentaire']} ]. \n\n Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl}  \n\n";
    $text_body .="-------------------------------\n\n";
    $text_body .= "SGT Teams,\n\n";
            
    $mail->Body    = $body;
    $mail->AltBody = $text_body;
            $mail->AddAddress($to);
     
            if($mail->Send()){
                       }
              /*try notify Student**************************************** */
                       if($uLevel=='etudiant'){
                       // $toStudent=$subMail['mail'];
                          $toStudent="binpc17@gmail.com";
                           $mail = new PHPMailer();
    $mail->CharSet='UTF-8'; 
    $mail ->IsSmtp();
   $mail ->SMTPDebug =0;
   $mail ->SMTPAuth =true;
   $mail ->SMTPSecure ='ssl';
 
    /*$mail ->Host ="mail.fstaulpgl.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@fstaulpgl.com";
   $mail ->Password="ulpgl@SgtGoma";
   $mail ->SetFrom("info@fstaulpgl.com","FSTA ULPGL");*/ 
   
   //$mail ->Host ="hp294.hostpapa.com";
   //$mail ->Port = 465; // or 587
   //$mail ->IsHTML(true);
   //$mail ->Username="info@egozola.com";
   //$mail ->Password="infoEgozola1422";
   
   //$mail ->SetFrom("info@egozola.com","SGT ULPGL");
   
   $mail ->Host ="mail.fstaulpgl.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username="info@fstaulpgl.com";
   $mail ->Password="ulpgl@SgtGoma";
   $mail ->SetFrom("info@fstaulpgl.com","FSTA ULPGL");
   
   
  $mail->Subject = "SGT-iMessage Message envoyé à : {$targetName['preNom']} {$targetName['nom']}";
            $body  = "<font size=\"4\"> Bonjour ". $_COOKIE['nom']." !</font>, <p>";
            $body .= "Sujet de TFE/TFC >> <font size=\"4\"> {$subMail['titre']} </font><p>";
            $body .= "-> Votre message a été envoyé avec succès: [ {$_POST['commentaire']} ]. <br> Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl} <p>";
            $body .= "-------------------------------<p>";
            $body .= "SGT Team, <br>";
            
                // Plain text body (for mail clients that cannot read HTML)
    $text_body  ="<font size=\"4\"> Bonjour ". $_COOKIE['nom']."! </font>, \n\n";
    $text_body .= "Sujet de TFE/TFC:>> {$subMail['titre']}\n\n";
   $text_body .= "-> Votre message a été envoyé avec succès: [ {$_POST['commentaire']} ]. \n\n Pour plus de détails, veuillez vous connecter pour consulter : {$webUrl}  \n\n";
    $text_body .="-------------------------------\n\n";
    $text_body .= "SGT Teams,\n\n";
    
    $mail->Body    = $body;
    $mail->AltBody = $text_body;
            $mail->AddAddress($toStudent);
     
            if($mail->Send()){
                       }
                       }
                        
                    }else{
                        $error_message="Impossible d'envoyer le message joint.";
                    }


                }else{
                    $error_message="Impossible de charger le fichier,il est trop lourd.";
                
                }
            }else{
                    $error_message="Ce format n'est pas permis.";

            }

        }
    else{
         $error_message="Veuillez ecrire un message.";
    }
    
}
?>
<h3 style="color: darkgreen"><center> Nouveau message <center></h3>
<div id='contenu' class='row'>
<div class='col-md-7 col-md-offset-2'>
   
    <?php echo"<a href='correctionn.php?id={$id}&dest={$dest}' class='btn btn-danger pull-right'>
        <span class='glyphicon glyphicon-arrow-right'></span> <strong> Retour</strong>
    </a>";
    ?>
    <br/><br/><br/>

        <?php
            if ($error_message != "") {
                echo '<div class="alert alert-danger"><strong>Erreur: </strong> ' . $error_message . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button></div>';
            }else if ($success_message != "") {
                echo '<div class="alert alert-success"><strong>Success: </strong> ' . $success_message . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button></div>';
            }
        ?>
    <strong> Sujet: </strong> <em>  <?php echo $subMail['titre'] ?> </em><br>
    <strong> Destinataire :</strong> <strong style="color: darkgreen"> <?php echo $targetName['nom']." ". $targetName['postNom']." | Email: ".$targetName['mail'] ?> </strong>
         <form action="" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive'>
                <tr>                               
                    <td><textarea placeholder="Entrer votre message" rows="6" type='text' name='commentaire' class='form-control'></textarea>
                </td>
                </tr>
                <tr>                 
                    <td> 
                        <div class="form-inline"> 
                        <input type='file' name='fichierTrav' class='form-control' /> 
                                  <button type="submit" class="btn btn-primary"> <span class="glyphicon glyphicon-send"> </span> <strong>Envoyer</strong</button>
                     </div>
                    </td>
                </tr>
            </table>
        </form>

    </div>
</div>

<?php
    // footer
include_once "layouti_footer.php";
?>