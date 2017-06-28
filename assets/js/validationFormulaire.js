//===================================================================================================
//== FONCTION :    Window onload                                   ==================================
//===================================================================================================
window.onload=function(){

	//=============================================
	//====== FONCTION :Submit =====================
	//=============================================
	if(document.forms[0]!=null)
	document.forms[0].onsubmit = function(form) {  console.log('submit');

		//reset erreurs formulaire
		var tabElementError = document.forms[0].elements;
		for(var i=0; i<tabElementError.length; i++){
			if(tabElementError[i].classList.contains("error")){
				tabElementError[i].classList.remove("error");
				var list = document.getElementById("bulleErreur");
				tabElementError[i].parentNode.removeChild(list);
			}
		}
	
		if(document.getElementsByClassName("retourEnvoiForm")[0]!=null)
			document.getElementsByClassName("retourEnvoiForm")[0].innerHTML = '';
		if(document.getElementsByClassName("retourEnvoiFormok")[0]!=null)
			document.getElementsByClassName("retourEnvoiFormok")[0].innerHTML = '';

		// tableau des erreurs
		var tab_erreur = new Array();

		//====== Formulaire contact =====================
		if(document.forms['contact']!=null){  
			//== nom ==
			if(document.forms[0].nom.value=="")
				tab_erreur['nom']="<b>Nom</b> obligatoire";
						//== nom ==
			if(document.forms[0].prenom.value=="")
				tab_erreur['prenom']="<b>Prénom</b> obligatoire";
	
			//== email ==
			if(document.forms[0].email.value=="")
				tab_erreur['email']="<b>Email</b> obligatoire";
			else if(!/^[\w.+-]{1,64}@[\w.-]{1,64}\.[\w.-]{2,6}$/i.test(document.forms[0].email.value) )
				tab_erreur['email']="<b>Email</b> non valide";
	
			//== objet ==
			if(document.forms[0].objet.value=="")
				tab_erreur['objet']="<b>Objet</b> obligatoire";
	
			//== message ==
			if(document.forms[0].message.value=="")
				tab_erreur['message']="<b>Message</b> obligatoire";
	
			//== human ==
			if(!document.forms[0].human.checked)
				tab_erreur['human']="<b>Human</b> obligatoire";	
		}	
		
		
		//====== Formulaire authentification =====================
		else if(document.forms['authentification']!=null){  
		
			//== login ==
			if(document.forms[0].login.value=="")
				tab_erreur['login']="<b>Login</b> obligatoire";
	
			//== mot de passe ==
			if(document.forms[0].pwd.value=="")
				tab_erreur['pwd']="<b>Password</b> obligatoire";
		}
		
		
		//====== Formulaire catégorie =====================
		else if(document.forms['categories']!=null){  
		
			//== categorie ==
			if(document.forms[0].categorie.value=="")
				tab_erreur['categorie']="<b>Catégorie</b> obligatoire";
	
		}
		
			//====== Formulaire authentification =====================
		else if(document.forms['authentification']!=null){  
		
			//== login ==
			if(document.forms[0].login.value=="")
				tab_erreur['login']="<b>Login</b> obligatoire";
	
			//== mot de passe ==
			if(document.forms[0].pwd.value=="")
				tab_erreur['pwd']="<b>Password</b> obligatoire";
		}
		
		
		//====== Formulaire article =====================
		else if(document.forms['article']!=null){  
		
			//== titre ==
			if(document.forms[0].titre.value=="")
				tab_erreur['titre']="<b>Titre</b> obligatoire";
				
			//== contenu ==
			if(document.forms[0].contenu.value=="")
				tab_erreur['contenu']="<b>Contenu</b> obligatoire";

			//== photo ==
			if(document.forms[0].photo.value!=""){  
				var img = new Image();
				console.log(document.forms[0].photo.files[0]);
				if(document.forms[0].photo.files[0].size>2000000)
					tab_erreur['photo']="<b>Photo</b> max 2Mo";
			}	
	
		}

		//====== Formulaire utilisateurs =====================
		else if(document.forms['utilisateurs']!=null){ 
		
			//== nom ==
			if(document.forms[0].nom.value=="")
				tab_erreur['nom']="<b>Nom</b> obligatoire";

			//== login ==
			if(document.forms[0].login.value=="")
				tab_erreur['login']="<b>Login</b> obligatoire";
	
			//== mot de passe ==
			if(document.forms[0].mdp.value=="")
				tab_erreur['mdp']="<b>Mot de passe</b> obligatoire";
			else if( document.forms[0].mdp.value.length < 4)
                tab_erreur['mdp']='<b>Mot de Passe</b> non valide (min 4 caractères)'; 

		}



		//======  AFFICHAGE DES ERREURS =====================
        form_err=false;     
             
        for(var valeur in tab_erreur){ 
            //document.monformulaire.valeur.style.backgoundColor='lightpink';
            document.forms[0].elements[valeur].setAttribute('class','error');
            var divBulle = document.createElement("div");
            document.forms[0].elements[valeur].parentNode.insertBefore(divBulle, document.forms[0].elements[valeur]); 
            divBulle.setAttribute('id','bulleErreur');
			divBulle.setAttribute('class','bulleErreur');
            divBulle.innerHTML = "<span><b>"+tab_erreur[valeur]+"</b></span>";
            form_err=true;  
        }
                         
        if(form_err) 
				return false;
        return true;
	}


			

}

	//=============================================
	//====== FONCTION : Confirmation Supprimer =====================
	//=============================================
	function confirm_supprimer(legende,param){ 
		if(confirm("Voulez vous supprimer "+decodeURIComponent(legende)+" ? "))
				window.location=param ;
	}


	//=============================================
	//====== FONCTION :Click message =====================
	//=============================================
	function openMessage(obj){
		obj.setAttribute('style','display:none');
		obj.parentNode.childNodes[2].nextElementSibling.setAttribute('style','display:visible');
	}

	function closeMessage(obj){
		obj.setAttribute('style','display:none');
		obj.parentNode.childNodes[2].previousElementSibling.setAttribute('style','display:visible');
	}



