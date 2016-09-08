jQuery ($)->
  for name, meta_key of art_PostTableColumns
    $item= $("##{name} a")
    href= $item.attr "href"
    orderby= href.match(/orderby=(\w+)/)[1]
    href= href.replace /orderby=(\w+)/, "orderby=meta_value"
    if href.match(/meta_key=\w+/)
      href= href.replace /meta_key=\w+/, "meta_key=#{meta_key}"
    else
      href= href + "&meta_key=#{meta_key}"
    $item.attr "href", href

    url= location.href
    if url.match(/orderby=meta_value/) and url.match(new RegExp("meta_key=#{meta_key}"))
      $item.parent()
        .addClass "sorted"
        .removeClass "sortable"
      if url.match(/order=asc/)
        $item.parent()
        .addClass "asc"
        .removeClass "desc"
      if url.match(/order=desc/)
        $item.parent()
        .addClass "desc"
        .removeClass "asc"
      if(url.match(/order=asc/))
        $item.attr "href": href.replace(/order=asc/, "order=desc")
      if(url.match(/order=desc/))
        $item.attr "href": href.replace(/order=desc/, "order=asc")
      $item.parent().siblings().each ->
        $("a", @).attr "href", (i, val)->
          val.replace /&meta_key=\w+/, ""
    else
      $item.parent()
        .removeClass "sorted"
        .addClass "sortable"
        #.removeClass "desc"
        # .removeClass "asc"
