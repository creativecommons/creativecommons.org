from routes import Mapper

from cc.engine.licenses import routing as licenses_routing

mapping = Mapper()
mapping.minimization = False
mapping.connect(
    "index", "/", controller="cc.engine.views:root_view")

mapping.extend(licenses_routing.licenses_routes)

# CC0
mapping.connect(
    "cc0_deed",
    "/publicdomain/zero/{version}/",
    code='CC0',
    controller="cc.engine.licenses.views:license_deed_view")
mapping.connect(
    "cc0_deed_lang",
    "/publicdomain/zero/{version}/deed.{target_lang}",
    code='CC0',
    controller="cc.engine.licenses.views:license_deed_view")
