cfrom routes.route import Route

chooser_routes = [
    Route("choose_index", "/",
          controller="cc.engine.chooser.views:chooser_view"),
    Route("choose_results_one", "/results-one",
          controller="cc.engine.chooser.views:choose_results_view"),
    ]
