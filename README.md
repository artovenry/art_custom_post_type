# custom_post_type
Wordpress plugin for custom post type.

# Usage
+ Create `/your_wp_theme_directory/custom_post_types/SomePostType.php`.
```php
<?
  class Event extends Artovenry\CustomPostType\Base{
  
  
  
  }
?>
```
+ Activate this plugin.
```bash
% wp plugin activate custom_post_type
```

+ That's all! For example, you can just use `Event` class from template files.
```html
<section>
  <?foreach(Event::take(5) as $item):?>
    <article>
      ...
      <footer>
        <p class="organization">
          Organization: <?= $item->organization ?>
        </p>
      </footer>
    </article>
</section>
```
