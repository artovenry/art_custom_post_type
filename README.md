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

# Feature
## Simple and useful APIs.

Retrieve *published and latest* "event" post and wrap this `WP_Post` object into `Event` object.

```php
$event= Event::take();
echo $event instanceof Event; //true
echo $event->post instanceof WP_Post; //true
```

Accessing one post's custom attributes(which are persisted into `wp_postmeta` table), `get`, `set`, and `delete` them.

```php
$event= Event::take();
$event->setMeta([
  "organization" => "Our friends",
  "location"     => "Meiji Jingu"
]);
$event->deleteMeta("scheduled_on");
$event->Event::take();

echo $event->location;      // "Meiji Jingu"
echo $event->organization;  // "Our friends"
echo $event->scheduled_on;  // null
```

notice: We just use WP's build-in postmeta APIs. Unlike general ORM mapper framework, our APIs (`setMeta`, `deleteMeta` ,,,) don't effect anything to reciever object, simply call WP's functions.

Validation? We offer nothing about it.

Callback? We offer two callback methods `after_save` and `before_save`.

