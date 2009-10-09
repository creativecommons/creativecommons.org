from zope.pagetemplate.pagetemplatefile import PageTemplateFile
from zope.tales.tales import ExpressionEngine
from zope.tales.tales import Context
from zope.i18n import translate

from zope.tales.expressions import PathExpr, StringExpr, NotExpr, DeferExpr
from zope.tales.expressions import SimpleModuleImporter
from zope.tales.pythonexpr import PythonExpr


class CCLPageTemplateFile(PageTemplateFile):
    def __init__(self, *args, **kwargs):
        self.target_language = None
        if kwargs.has_key('target_language'):
            self.target_language = kwargs['target_language']
            kwargs.pop('target_language')
        PageTemplateFile.__init__(self, *args, **kwargs)

    def pt_getEngine(self):
        e = CCLExpressionEngine(target_language=self.target_language)
        reg = e.registerType
        for pt in PathExpr._default_type_names:
            reg(pt, PathExpr)
        reg('string', StringExpr)
        reg('python', PythonExpr)
        reg('not', NotExpr)
        reg('defer', DeferExpr)
        e.registerBaseName('modules', SimpleModuleImporter())
        return e


class CCLExpressionEngine(ExpressionEngine):
    def __init__(self, *args, **kwargs):
        self.target_language = None
        if kwargs.has_key('target_language'):
            self.target_language = kwargs['target_language']
            kwargs.pop('target_language')
        ExpressionEngine.__init__(self, *args, **kwargs)

    def getContext(self, contexts=None, **kwcontexts):
        if contexts is not None:
            if kwcontexts:
                kwcontexts.update(contexts)
            else:
                kwcontexts = contexts
        return CCLContext(
            self, kwcontexts, target_language=self.target_language)


class CCLContext(Context):
    def __init__(self, *args, **kwargs):
        self.target_language = None
        if kwargs.has_key('target_language'):
            self.target_language = kwargs['target_language']
            kwargs.pop('target_language')
        Context.__init__(self, *args, **kwargs)

    def translate(self, msgid, domain=None, mapping=None, default=None):
        return translate(msgid, domain, mapping,
                         default=default, target_language=self.target_language)
