assert= require "power-assert"
describe "Basic", ->
  before do require("testium/mocha")
  beforeEach ->
    @browser.navigateTo "http://127.0.0.1:3000/wp-login.php"
    @browser.setValue "#user_login", "admin"
    @browser.setValue "#user_pass", "pass"
    @browser.click "#wp-submit"

  context "when reached event editor page; ", ->
    beforeEach ->
      @browser.navigateTo "http://127.0.0.1:3000/wp-admin/post-new.php?post_type=event"

    it "should display four meta boxes", ->
      for item in ["option", "hoge", "shoot", "woops"]
        @browser.assert.elementExists("#art_event_#{item}")

    it "should persist show_at_home and display your input value.", ->
      @field= "input[name='art_meta_boxes[event][show_at_home]']"

      @browser.setValue @field, "1"
      @browser.click "#publish"
      #@browser.assert.elementHasValue @field, "1"
