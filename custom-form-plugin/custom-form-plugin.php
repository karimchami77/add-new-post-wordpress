<?php
/*
Plugin Name: Custom Form Plugin
Description: Un plugin qui ajoute un formulaire avec deux champs (titre et texte) et crée un nouvel article non publié lorsqu'il est soumis.
Version: 1.0
Author: Votre nom
Author URI: Votre URL
License: GPL2
*/

// Crée un shortcode qui affiche le formulaire
function custom_form_shortcode() {
  ob_start(); ?>

  <form id="custom-form" method="post">
    <div>
      <label for="title">Titre:</label>
      <input type="text" id="title" name="title" required>
    </div>
    <div>
      <label for="text">Texte:</label>
      <textarea id="text" name="text" required></textarea>
    </div>
    <button type="submit">Envoyer</button>
  </form>

  <?php
  // Retourne le formulaire
  return ob_get_clean();
}
add_shortcode( 'custom-form', 'custom_form_shortcode' );

// Traitement du formulaire lorsqu'il est soumis
function process_custom_form() {
  global $wpdb;
  // Vérifie si le formulaire a été soumis
  if (isset($_POST['title']) && isset($_POST['text'])) {
    $title = $_POST['title'];
    $text = $_POST['text'];
    // Vérifie si un article avec le même titre existe déjà
    $existing_post = get_page_by_title($title, 'OBJECT', 'post');
    if ($existing_post) {
      // Affiche un message d'erreur si un article avec le même titre existe déjà
      echo '<p>Un article avec le titre "' . $title . '" existe déjà. Veuillez choisir un titre différent.</p>';
    } else {
      // Crée un nouvel article non publié avec les champs du formulaire
      $post_id = wp_insert_post(array(
        'post_title' => $title,
        'post_content' => $text,
        'post_status' => 'draft'
      ));
      // Envoie un e-mail à l'adresse e-mail de l'administrateur avec le titre et le texte du message
      wp_mail(get_option('admin_email'), 'Nouvel article créé', 'Un nouvel article intitulé "' . $title . '" a été créé.');
      // Affiche un message de confirmation
      echo '<p>L\'article intitulé "' . $title . '" a été créé avec succès.</p>';
    }
  }
}
// Appelle la fonction de traitement du formulaire lors de l'exécution de l'hook "init"
add_action('init', 'process_custom_form');

?>
