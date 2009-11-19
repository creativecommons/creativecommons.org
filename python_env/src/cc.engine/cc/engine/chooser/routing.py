from routes.route import Route

chooser_routes = [
    Route("licenses_index", "/",
          controller="cc.engine.chooser.views:chooser_view"),
    ]
