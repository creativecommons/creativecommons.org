from routes import Mapper

mapping = Mapper()
mapping.minimization = False
mapping.connect(
    "index", "/", controller="cc.engine.views:root_view")
mapping.connect(
    "licenses_index", "/licenses/", controller="cc.engine.views:licenses_view")
mapping.connect(
    "specific_license", "/licenses/{code}/{version}/",
    controller="cc.engine.views:license_deed_view")
mapping.connect(
    "specific_license_rdf",
    "/licenses/{code}/{version}/rdf/",
    controller="cc.engine.views:license_rdf_view")
mapping.connect(
    "specific_license_legalcode",
    "/licenses/{code}/{version}/legalcode/",
    controller="cc.engine.views:license_legalcode_view")
mapping.connect(
    "specific_license_legalcode_plain",
    "/licenses/{code}/{version}/legalcode-plain/",
    controller="cc.engine.views:license_legalcode_plain_view")
mapping.connect(
    "specific_license_jurisdiction",
    "/licenses/{code}/{version}/{jurisdiction}/",
    controller="cc.engine.views:license_deed_view")
mapping.connect(
    "specific_license_rdf_jurisdiction",
    "/licenses/{code}/{version}/{jurisdiction}/rdf/",
    controller="cc.engine.views:license_rdf_view")
mapping.connect(
    "specific_license_legalcode_jurisdiction",
    "/licenses/{code}/{version}/jurisdiction}/legalcode/",
    controller="cc.engine.views:license_legalcode_view")
mapping.connect(
    "specific_license_legalcode_plain_jurisdiction",
    "/licenses/{code}/{version}/jurisdiction}/legalcode-plain/",
    controller="cc.engine.views:license_legalcode_plain_view")
