from routes import Mapper

from cc.engine.licenses import routing as licenses_routing

mapping = Mapper()
mapping.minimization = False
mapping.connect(
    "index", "/", controller="cc.engine.views:root_view")

mapping.extend(licenses_routing.licenses_routes, '/licenses')
mapping.extend(licenses_routing.cc0_routes, '/publicdomain/zero')
