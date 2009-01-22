from zope.interface import Interface

class IRdfaGenerator(Interface):

    def __call__(license_uri, data_mapping):
        pass
