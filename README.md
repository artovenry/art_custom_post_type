# custom_post_type
Wordpress plugin for custom post type.

# Usage
+ Create `/your_wp_theme_directory/custom_post_types/SomePostType.php`.
```php
<?
  class Event extends Artovenry\CustomPostType\Base{
    static $post_type_options=[
      "name"=>    "event",
      "label"=>   "Our Big Event!",
    ];
    static $meta_attributes=["organization", "scheduled_on", "location"];
    static $meta_boxes= [
      ["name"=>, "extra", "label"=> "Extra Informations"],
      ["name"=>, "option", "label"=> "Options"]
    ];
    static $post_list_options=[
      "order"=>["organization", "title", "scheduled_on", "location", "date"],
      "columns"=>[
        "organization"=>["label"=>""],
        "scheduled_on"=>["label"=>"Scheduled On",
          "render"=>function($record){echo "<b style='color:red;'>{$record->scheduled_on}</b>";},
        ],
        "location"=>["label"=>"Loc."]
      ]
    ];
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
