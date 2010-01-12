from routes.route import Route

chooser_routes = [
    Route("choose_index", "/",
          controller="cc.engine.chooser.views:chooser_view"),
    Route("choose_results_one", "/results-one",
          controller="cc.engine.chooser.views:choose_results_view"),
    Route("choose_get_html", "/get-html",
          controller="cc.engine.chooser.views:get_html"),
    Route("choose_get_rdf", "/get-rdf",
          controller="cc.engine.chooser.views:get_rdf"),
    Route("choose_wiki", "/wiki",
          controller="cc.engine.chooser.views:choose_wiki_redirect"),
    Route("non_web_popup", "/non-web-popup",
          controller="cc.engine.chooser.views:non_web_popup"),
    Route("work_html_popup", "/work-html-popup",
          controller="cc.engine.chooser.views:work_email_popup"),
    Route("work_html_popup", "/work-email",
          controller="cc.engine.chooser.views:work_email_send"),
    ]
