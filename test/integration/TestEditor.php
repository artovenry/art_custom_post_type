<?
class TestEditor extends Artovenry\CustomPostType\Integration\TestCase{
  function testBasic(){
    $this->navigateTo("/wp-admin/post-new.php?post_type=type_one");
    $this->write("#title", "Semishigure!");
    $this->click("#publish");
    $this->assertElementHasValue("#title", "Semishigure!",
      "When reached type-one editor page(no metaboxes),should save new post normally."
    );
  }
  function testEventEditorPage(){
    $this->navigateTo("/wp-admin/post-new.php?post_type=event");
    foreach(["option", "hoge", "shoot", "woops"] as $item)
      $this->assertElementExists("#art_event_{$item}");
    $field= "input[name='art_meta_boxes[event][show_at_home]']";
    $this->write($field, 1);
    $this->click("#publish");
    $this->assertElementHasValue($field, "1",
      "should persist show_at_home and display your input value, however auto-drafted."
    );
  }
  function testPostListTable(){
    $this->navigateTo("/wp-admin/edit.php?post_type=event");
    $column_headers= $this->find(".wp-list-table thead th:not(#cb)");
    $this->assertCount(4, $column_headers);
  }
}
