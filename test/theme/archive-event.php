<!DOCTYPE html>
<html>
  <body <? body_class(); ?>>
    <? the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
    <?if(have_posts()): ?>
      <?while(have_posts()): ?>
        <? the_post(); ?>
        <article title="<? the_title();?>">
          <? the_title();?>
          <?= $post->post_date; ?>
        </article>
      <?endwhile; ?>
    <?endif; ?>
  </body>
</html>
