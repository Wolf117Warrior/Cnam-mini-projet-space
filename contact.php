<!DOCTYPE HTML>
<!--
	Theory by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>


<!-- Head -->

   <?php include("./include/head.php"); ?>

	<body>

		<!-- LOGO --> <!-- Bannière -->
			<section id="banner">
			</section>



<!-- NAVBAR -->

<header id="header">
    <a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
  </div>
</header>


<section id="one" class="wrapper">

  <div class="inner">
    <nav id="nav">
      <a href="index.php">Accueil</a>
      <a href="blog.php">Blog</a>
      <a href="portofolio.php">Portofolio</a>
      <a href="contact.php">Contact</a>
    </nav>
  </div>

</section>

<!-- Form -->

</section id="contact" class="content-wrapper">
<div class="inner">
  <h3>Formulaire de contact</h3>

  <form method="post" action="#">
    <div class="row uniform">
      <div class="6u 12u$(xsmall)">
        <input type="text" name="name" id="name" value="" placeholder="Nom" />
      </div>
      <div class="6u$ 12u$(xsmall)">
        <input type="email" name="email" id="email" value="" placeholder="Courriel" />
      </div>
      <div class="6u 12u$(xsmall)">
        <input type="text" name="objet" id="objet" value="" placeholder="Objet" />
      </div>


      <!-- Break -->
      <div class="12u$">
        <textarea name="message" id="message" placeholder="Entrer votre message" rows="6"></textarea>
      </div>
      <!-- Break -->
      <div class="6u$ 12u$(small)">
        <input type="checkbox" id="human" name="human" checked>
        <label for="human">Je ne suis pas un robot.</label>
      </div>

      <div class="12u$">
        <ul class="actions">
          <li><input type="submit" value="Envoie" /></li>
          <li><input type="reset" value="Effacer" class="alt" /></li>
        </ul>
      </div>
    </div>
  </form>

</section>

		<!-- Footer -->

		<?php include("./include/footer.php"); ?>

		<!-- Scripts -->

		<?php include("./include/scripts.php"); ?>

	</body>
</html>
