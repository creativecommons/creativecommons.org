from repoze.bfg.router import make_app

def app(global_config, **kw):
    """ This function returns a repoze.bfg.router.Router object.  It
    is usually called by the PasteDeploy framework during ``paster
    serve``"""
    # paster app config callback
    from cc.engine.models import get_root
    import cc.engine
    return make_app(get_root, cc.engine, options=kw)

