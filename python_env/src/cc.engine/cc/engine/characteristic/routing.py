from routes.route import Route

characteristic_routes = [
    Route('characteristic_by', '/by', characteristic='by',
          controller="cc.engine.characteristic.views:characteristic_view"),
    Route('characteristic_nc', '/nc', characteristic='nc',
          controller="cc.engine.characteristic.views:characteristic_view"),
    Route('characteristic_nc', '/nd', characteristic='nd',
          controller="cc.engine.characteristic.views:characteristic_view"),
    Route('characteristic_sa', '/sa', characteristic='sa',
          controller="cc.engine.characteristic.views:characteristic_view")]
