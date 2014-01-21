"""Non-ZCML registrations for cc.licenze."""

from zope import component

import interfaces
import zero

# CC0
zero_chooser = zero.ZeroSelector()
component.provideUtility(zero_chooser, interfaces.ILicenseSelector, 'zero')
