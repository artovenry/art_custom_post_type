assert= require "power-assert"
root= "http://127.0.0.1:3000"
adminRoot= [root, "wp-admin"].join("/")

describe "Basic", ->
  before do require("testium/mocha")
  beforeEach ->
    @browser.navigateTo "#{root}/wp-login.php"
    @browser.setValue "#user_login", "admin"
    @browser.setValue "#user_pass", "pass"
    @browser.click "#wp-submit"

  context "when reached event post list table;", ->
    beforeEach ->
      @browser.navigateTo "#{adminRoot}/edit.php?post_type=event"
    it "should show customized table columns and raws.", ->
      column_headers= @browser.getElements(".wp-list-table thead th:not(#cb)")
      assert column_headers.length is 4
  #     @browser.assert.elementHasAttributes ".wp-list-table thead th:nth-of-child(1)",
  #       id: "show_at_home"
  #     @browser.assert.elementHasAttributes ".wp-list-table thead th:nth-of-child(2)",
  #       id: "date"
  #     @browser.assert.elementHasAttributes ".wp-list-table thead th:nth-of-child(3)",
  #       id: "title"
  #     @browser.assert.elementHasAttributes ".wp-list-table thead th:nth-of-child(4)",
  #       id: "author"

  context "when reached type-one editor page(no metaboxes);", ->
    beforeEach ->
      @browser.navigateTo "#{adminRoot}/post-new.php?post_type=type_one"
    it "should save new post normally.", ->
      @browser.setValue "#title", "Semishigure!"
      @browser.click "#publish"
      @browser.assert.elementHasAttributes "#title", {value: "Semishigure!"}

  context "when reached event editor page; ", ->
    beforeEach ->
      @browser.navigateTo "#{adminRoot}/post-new.php?post_type=event"

    it "should display four meta boxes.", ->
      for item in ["option", "hoge", "shoot", "woops"]
        @browser.assert.elementExists("#art_event_#{item}")

    it "should persist show_at_home and display your input value.", ->
      @field= "input[name='art_meta_boxes[event][show_at_home]']"
      @browser.setValue "#title", "Yu-dachi"
      @browser.setValue @field, "1"
      @browser.click "#publish"
      @browser.assert.elementHasValue @field, "1"

    it "should also persist meta attribute when auto-drafted (with no post attributes).", ->
      @field= "input[name='art_meta_boxes[event][show_at_home]']"
      @browser.setValue @field, "1"
      @browser.click "#publish"
      @browser.assert.elementHasValue @field, "1"
