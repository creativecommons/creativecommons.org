import webtest

from cc.engine import app, staticdirect


def test_remote_staticdirect():
    sd = staticdirect.RemoteStaticDirect('/statik')
    assert sd('/foo/') == '/statik/foo/'
    assert sd('/foo/bar/baz') == '/statik/foo/bar/baz'
    assert sd('blah') == '/statik/blah'


def test_multi_remote_staticdirect():
    sd = staticdirect.MultiRemoteStaticDirect(
        {'images': 'http://img.example.org/images/',
         'js': '/statik/js/',
         'css': '/statik/stylesheets/'})
    assert sd('/images/intarwebs.jpg') == \
        'http://img.example.org/images/intarwebs.jpg'
    assert sd('/js/joovascreept.js') == '/statik/js/joovascreept.js'
    assert sd('/css/stylish.css') == '/statik/stylesheets/stylish.css'


def test_request_dot_staticdirect():
    testapp = webtest.TestApp(
        app.CCEngineApp(
            staticdirect.RemoteStaticDirect('/statik/'), {}))
    response = testapp.get('/licenses/by/3.0/')
    assert u'/statik/images/information.png' in response.unicode_body
    assert u'/statik/includes/deed3.css' in response.unicode_body


def test_static_app_factory():
    testapp = webtest.TestApp(
        staticdirect.static_app_factory(
            None, resource_path='cc.engine:templates'))
    response = testapp.get('/test/bunnies.pt')
    assert u'Welcome to the bunny field!' in response.body
