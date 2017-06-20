<footer id="footer">
  <div class="inner">
    <div class="flex">
      <div class="copyright">
        &copy; Source du design : <a href="https://templated.co">TEMPLATED</a>
      </div>

      <div>

        <!-- messages -->
        <section id="one">

          <div>

          <?php
            //--------------------------------//
            //--- affichage des candidat et le nfa ---//
            //--------------------------------//

              $result=$maBase->query("SELECT ID_utilisateur, NOM_utilisateur, COURS_nfa FROM cnamcp09_utilisateurs WHERE COURS_nfa='nfa021' ");
                if($result) {
                  while($user=$result->fetch()) {
                    $user_id = $user['ID_utilisateur'];
                    $user_nom = html_entity_decode($user['NOM_utilisateur']);?>

                <b><?php echo $user_nom; ?></b>
                <?php echo html_entity_decode($user['COURS_nfa']); ?>
          <?php           }
                    }
          ?>
         </div>
        </section>
    </div>

    <div>
        <a href="../mention.php">Mentions légales</a>
    </div>

    </div>
  </div>
</footer>
