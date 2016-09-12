<!DOCTYPE html>
<html>
  <body <? body_class(); ?>>
    <h1>test</h1>
    <?if(is_single()): ?>
      <? the_post(); ?>
      <article title="<? the_title();?>"><? the_title();?></article>
    <?endif; ?>
  </body>
</html>
