<?php
 
    $post = get_post($_POST['id']);
 
?>
    <div id="single-post post-<?php the_ID(); ?>">
 
    <?php while (have_posts()) : the_post(); ?>
 
                <?php the_title();?>
 
                <?php the_content();?>
 
    <?php endwhile;?> 
 
    </div>