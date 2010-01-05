from routes import Mapper

from cc.engine.licenses import routing as licenses_routing
from cc.engine.chooser import routing as chooser_routing

mapping = Mapper()
mapping.minimization = False
mapping.connect(
    "index", "/", controller="cc.engine.views:root_view")

mapping.connect(
    '/publicdomain/', controller="cc.engine.licenses.views:publicdomain_view")

mapping.extend(licenses_routing.licenses_routes, '/licenses')
mapping.extend(licenses_routing.cc0_routes, '/publicdomain/zero')
mapping.extend(chooser_routing.chooser_routes, '/choose')
mapping.connect(
    'staticserve', '/static/{filename:.*?}',
    controller="cc.engine.views:staticserve_view")
