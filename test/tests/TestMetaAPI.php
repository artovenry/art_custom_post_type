<?
class TestMetaAPI extends  Artovenry\CustomPostType\TestCase{
  function setup(){
    parent::setup();
    // global $wpdb;
    // $wpdb->query("delete from {$wpdb->postmeta} where meta_key like '%test_meta_api_%'");
    $this->one= TypeOne::build($this->one);
  }

  function test(){
    global $wpdb;
    //hogeはDBにないので本来はnullかfalseが欲しい
    $this->assert(get_post_meta($this->one->ID, "hoge", true) === "");

    //インサートされる。post_idのrowが存在しなかったので、インサートされたローのmeta_idを返す
    $result= update_post_meta($this->one->ID, "hoge", "");
    $meta_id= $wpdb->query("select meta_id from {$wpdb->postmeta} where meta_key = 'hoge' limit 1");
    $this->assert($result === $meta_id);
    //hoge： ""　なので、空文字が返される
    $this->assert(get_post_meta($this->one->ID, "hoge", true) === "");

    //rowがhoge="boge"で上書きされる。上書きなのでtrueが帰る
    $this->assert(update_post_meta($this->one->ID, "hoge", "boge") === true);
    $this->assert(get_post_meta($this->one->ID, "hoge", true) === "boge");

    //同じ値で上書きしようとするとfalseが返ってくる。でもこれはtrueにしたい
    $this->assert(update_post_meta($this->one->ID, "hoge", "boge") === false);

    //空文字列で上書きする。上書きなのでtrueが帰る
    $this->assert(update_post_meta($this->one->ID, "hoge", "") === true);

    //hogeを削除,trueが帰る
    $this->assert(delete_post_meta($this->one->ID, "hoge") === true);
    $result= $wpdb->get_results("select * from {$wpdb->postmeta} where meta_key= 'hoge'");
    $this->assertCount(0, $result);

  }


}
