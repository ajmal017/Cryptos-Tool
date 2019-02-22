<div id='header' style='text-align:left;'><div id='logo' style='margin-top:7px'><img src="images/logo3.png" width="154" height="43" /></div>
 <div style='float:left;margin-left:170px;padding-top:5px;	font-weight:bold;'><?php echo $esp['titre'] ?></div>      
        
<div class='haut_header'>
  <ul id="menu" style='padding-right:60px'>

        <li>
               <a href="#"><?php echo $user['prenom']."&nbsp;".$user['nom'] ?></a>
                <ul>
                        <li><a href="espace.php?affiche=mdp" style=''>Mes parametres</a></li>
                        <?php if($_SESSION['super_admin']==1 OR $_SESSION['admin']==1){ ?>
                        <li><a href="configuration.php?afcat=config">Configuration</a>
                        <?php } ?>
                       </li>
                        <li><a href="index.php?deconnexion">D&eacute;connexion</a></li>
                </ul>
        </li>
</ul>
</div>

   

        

   
        
<div class='bas_header'>
				
<div <?php if($afcat=='0'){ echo "class='navigation_encours'";}else{echo "class='navigation'";} ?>style='' onClick="Javascript: window.location='accueil.php?afcat=0'">Accueil</div>
<div <?php if($afcat=='1000'){ echo "class='navigation_encours'";}else{echo "class='navigation'";} ?>style='' onClick="Javascript: window.location='accueil.php?afcat=1000&affiche=listing'">Rentabilit&eacute;s</div>

               <?php $ini_cat=mysqli_query($wikeo,"SELECT * FROM categories_groupes t LEFT JOIN categories c ON c.id_groupe_cat=t.id_groupe_cat WHERE t.id_espace=".$_SESSION['espace']." AND t.id_cat_parent=0 AND t.id_groupe_parent=0 order by ordre");
			   while($cat=mysqli_fetch_assoc($ini_cat)){ 
			   
			   $prep_ini_quest="SELECT q.id_question, d.id_groupe FROM questions q  LEFT JOIN questions_droits d ON q.id_question=d.id_question WHERE q.id_cat_princ=".$cat['id_cat']." AND (d.id_util=".$_SESSION['user']." OR d.id_groupe=".$usergroupe['id_groupe'].")";
			  $ini_quest=mysqli_query($wikeo,$prep_ini_quest);
			  if(mysqli_num_rows($ini_quest)>=1){$affiche_rub=true;}else{$affiche_rub=false;}
			  
			  $ini_droits="SELECT id_cat FROM categories_droits WHERE id_cat=".$cat['id_cat']." AND (id_util=".$_SESSION['user']." OR id_groupe=".$usergroupe['id_groupe'].")";
			  $droits=mysqli_query($wikeo,$ini_droits);
			  if(mysqli_num_rows($droits)>=1){$affiche_rub=true;}
			   
			   ?>
				 <?php if($affiche_rub==true){ ?> <div <?php if($afcat==$cat['id_cat']){ echo "class='navigation_encours'";}else{echo "class='navigation'";} ?>' onClick="Javascript: window.location='accueil.php?afcat=<?php echo $cat['id_cat'] ?>&affiche=questions'"><?php echo $cat['titre_cat'] ?></div><?php } ?>
			<?php } ?>
</div>
            </div>