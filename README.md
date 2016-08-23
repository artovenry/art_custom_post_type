# custom_post_type
Wordpress plugin for custom post type.

# Usage
+ Create `/your_wp_theme_directory/models/SomePostType.php`.
```php
<?
  class Event extends Artovenry\CustomPostType\Base{
    static $post_type_options=[
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

Accessing one post's custom attributes(which are persisted into `wp_postmeta` table), `get`, `set`, and `delete` them (You can simply define themes attributes by declaring static attribute or method named `meta_attributes`).

Notice: `post_type` is automatically guessed and registered into WP from **class name**. For example, class `Event` will be interpretated to `event`, class `ScheduledEvent` will be `scheduled_event`. `post_type`'s length  is **limited 20chars**.

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

## Validation

We do support **nothing** about it.

## Callback

We offer two callback methods `after_save` and `before_save`.

```php
class Information extends Artovenry\CustomPostType\Base{
  
}



## Metabox

You can define multiple metaboxes for each custom_post_type classes via static attribute or method named `meta_boxes`. We basically use **Haml** for rendering engine.

### Simple
```php
class ExamplePostType extends Artovenry\CustomPost\Base{
  static $meta_boxes=["name"=>'options', "label"=> "Please set your options!"];
  //...
}
```
will render metabox with template (do not forget `.html.haml` or `.php` extension) `{TEMPLATEPATH}/models/meta_boxes/example_post_type/options.html.haml`, or `{TEMPLATEPATH}/models/meta_boxes/example_post_type/options.php`.

### You can define your own template file.
```php
  static $meta_boxes=["name"=>'options', "label"=> "OPTS", "template"=>"options"];
}
```
In this case, template file is `{TEMPLATEPATH}/meta_boxes/options.html.haml(or .php)`.

### You can dynamically define your rendering method by specifing its static method's name, or callable value.
```php
  static $meta_boxes=["name"=>"options", "render"=>"render_options_box"];
  //...
  static function render_options_box(){
    //outputs your meta box,,,
  }

  //or with callable style...
  static $meta_boxes=["name"=>"options", "render"=>[$this->meta_renderer, "render"]]

```
