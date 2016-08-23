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

Accessing one post's meta attributes(which are persisted into `wp_postmeta` table), `get`, `set`, and `delete` them (You can simply define themes attributes by declaring static attribute or method named `meta_attributes`).

Meta attributes are scalar values(boolean, integer, float, string) Non-scalar value, such as arrays, objects and recources cannot assign into meta attributes.

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

Notice: We just use WP's build-in postmeta APIs. Unlike general ORM mapper framework, our APIs (`setMeta`, `deleteMeta` ,,,) don't effect anything to reciever object, simply call WP's functions.

## Metabox

You can define multiple metaboxes for each custom_post_type classes via static attribute or method named `meta_boxes`. We basically use **Haml** for rendering engine.

Notice: `$meta_boxes` and `$meta_attributes` are independent. You can define `$meta_attributes` without defining `$meta_boxes`. Of course, you will need to define `$meta_boxes` if you want users to edit your meta attributes in WP's edit interface.

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

## Callback
When saving a post, POSTed `meta_attributes` will be automatically persisted(**added** or **updated**) into wp_postmeta table(via WP's action hook: `save_post`), when CSRF authorized. Otherwise, this callback is skipped (meta_attributes **will not persisted**).

Make sure meta attributes which are not POSTed will not be deleted. When you do not prefer orphaned meta attributes, you will need to define your custom callback to destroy them.

**$meta_boxes** generates its own csrf token (per meta_box) and render hidden  input field with `_art_nonce_{template's name}` (eg, `_art_nonce_item_price`).
CSRF authorization check this token and authorize your POSTed meta_attributes.

You can define two custom callback methods `after_save` and `before_save`. `after_save` is invoked within WP's `save_post` hook, just after it's POSTed meta_attributes are persisted. `before_save` is invoked within WP's `wp_insert_post_data` hook.

Notice: If CSRF authorization failed, `after_save` callback is canceled.

This will set "updated_at" meta attribute to current UNIX timestamp when updating a post:

```php
class Information extends Artovenry\CustomPostType\Base{
  static $meta_atributes= ["updated_at"];
  static function after_save($post_id, $post, $updated){
    if(!$updated)return;
    $post->set_meta("updated_at", time()); //$post is a instance of Information, not a WP_Post
  }
}
```

This will truncate "post_title" attribute within 20 words before save a post.

```php
class Information extends Artovenry\CustomPostType\Base{
  static function before_save($sanitized, $raw){
    $sanitized["post_title"]= wp_trim_words($sanitized["post_title"], 20);
    return $sanitized;  //You must return $sanitized.
  }
}
```

## Validation

We do support **nothing** about it.
