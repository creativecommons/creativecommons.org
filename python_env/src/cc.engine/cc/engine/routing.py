from routes import Mapper

mapping = Mapper()
mapping.minimization = False
mapping.connect(
    "index", "/", controller="cc.engine.views:root_view")
mapping.connect(
    "licenses_index", "/licenses/", controller="cc.engine.views:licenses_view")
mapping.connect(
    "specific_license", "/licenses/{code}/{version}/",
    controller="cc.engine.views:specific_licenses_router_basic")
