"""
cc.engine
=========

See README.txt.

Copyright 2007 Nathan R. Yergler, Creative Commons <nathan@creativecommons.org>
"""

from zope.component import adapter
from zope.app.appsetup.interfaces import IDatabaseOpenedWithRootEvent
from zope.app.publication.zopepublication import ZopePublication

import cc.engine.chooser
import cc.engine.characteristic
import cc.engine.licenses.catalog
import cc.engine.publicdomain.catalog

@adapter(IDatabaseOpenedWithRootEvent)
def init(event):
    """Subscribe to database open events to initialize our objects."""

    db_root = event.database.open().root()[ZopePublication.root_name]
                                           
    # check for the license chooser
    if db_root.get('license', None) is None:
        db_root['license'] = cc.engine.chooser.LicenseEngine()

    # check for the deed catalog
    if db_root.get('licenses', None) is None:
        db_root['licenses'] = cc.engine.licenses.catalog.LicenseCatalog()

    # check for the public domain catalog
    if db_root.get('publicdomain', None) is None:
        db_root['publicdomain'] = cc.engine.publicdomain.catalog.PublicDomainCatalog()

    # check for the backup characteristic handler
    if db_root.get('characteristic', None) is None:
        db_root['characteristic'] = cc.engine.characteristic.Characteristics()
