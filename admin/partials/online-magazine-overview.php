<h1>Rivista del Magazzeno Storico Verbanese</h1>

<ul>
    <?php if(current_user_can('edit_others_posts'))  : ?>
    <li><a href="<?php echo admin_url() . 'edit.php?post_type=onlimag-issue'; ?>">Riviste</a></li>
    <li><a href="<?php echo admin_url() . 'edit-tags.php?taxonomy=onlimag-rubric&post_type=onlimag-article'; ?>">Rubriche</a></li>
    <?php endif; ?>
    <li><a href="<?php echo admin_url() . 'edit.php?post_type=onlimag-article'; ?>">Articoli</a></li>

</ul>
