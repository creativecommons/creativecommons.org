from repoze.bfg.view import static

import pkg_resources

static_view = static(
    pkg_resources.resource_filename(
        'cc.engine', 'resources'))
