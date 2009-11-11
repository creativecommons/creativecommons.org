from routes import Mapper

mapping = Mapper()
mapping.minimization = False
mapping.connect(
    "index", "/", controller="cc.engine.views:root_view")
mapping.connect(
    "licenses_index", "/licenses/", controller="cc.engine.views:licenses_view")
mapping.connect(
    "license_deed",
    "/licenses/{code}/{version}/",
    controller="cc.engine.views:license_deed_view")
mapping.connect(
    "license_deed_lang",
    "/licenses/{code}/{version}/deed.{target_lang}",
    controller="cc.engine.views:license_deed_view")
mapping.connect(
    "license_rdf",
    "/licenses/{code}/{version}/rdf",
    controller="cc.engine.views:license_rdf_view")
mapping.connect(
    "license_legalcode",
    "/licenses/{code}/{version}/legalcode",
    controller="cc.engine.views:license_legalcode_view")
mapping.connect(
    "license_legalcode_plain",
    "/licenses/{code}/{version}/legalcode-plain",
    controller="cc.engine.views:license_legalcode_plain_view")
mapping.connect(
    "license_deed_jurisdiction",
    "/licenses/{code}/{version}/{jurisdiction}/",
    controller="cc.engine.views:license_deed_view")
mapping.connect(
    "license_deed_lang_jurisdiction",
    "/licenses/{code}/{version}/{jurisdiction}/deed.{target_lang}",
    controller="cc.engine.views:license_deed_view")
mapping.connect(
    "license_rdf_jurisdiction",
    "/licenses/{code}/{version}/{jurisdiction}/rdf",
    controller="cc.engine.views:license_rdf_view")
mapping.connect(
    "license_legalcode_jurisdiction",
    "/licenses/{code}/{version}/jurisdiction}/legalcode",
    controller="cc.engine.views:license_legalcode_view")
mapping.connect(
    "license_legalcode_plain_jurisdiction",
    "/licenses/{code}/{version}/jurisdiction}/legalcode-plain",
    controller="cc.engine.views:license_legalcode_plain_view")

# CC0
mapping.connect(
    "cc0_deed",
    "/publicdomain/zero/{version}/",
    code='CC0',
    controller="cc.engine.views:license_deed_view")
mapping.connect(
    "cc0_deed_lang",
    "/publicdomain/zero/{version}/deed.{target_lang}",
    code='CC0',
    controller="cc.engine.views:license_deed_view")
