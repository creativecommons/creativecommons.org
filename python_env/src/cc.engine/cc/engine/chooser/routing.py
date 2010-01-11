from routes.route import Route

chooser_routes = [
    Route("choose_index", "/",
          controller="cc.engine.chooser.views:chooser_view"),
    Route("choose_results_one", "/results-one",
          controller="cc.engine.chooser.views:choose_results_view"),
    Route("choose_wiki", "/wiki",
          controller="cc.engine.chooser.views:choose_wiki_redirect"),
    Route("non_web_popup", "/non-web-popup",
          controller="cc.engine.chooser.views:non_web_popup"),
    ]
